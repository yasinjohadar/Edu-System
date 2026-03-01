<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OnlineLecture;
use App\Models\Subject;
use App\Models\Section;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OnlineLectureController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:lecture-list')->only('index', 'show');
        $this->middleware('permission:lecture-create')->only('create', 'store');
        $this->middleware('permission:lecture-edit')->only('edit', 'update');
        $this->middleware('permission:lecture-delete')->only('destroy');
    }

    public function index(Request $request)
    {
        $lecturesQuery = OnlineLecture::with(['subject', 'section.class', 'teacher.user'])->orderBy('created_at', 'desc');

        if ($request->filled('subject_id')) {
            $lecturesQuery->where('subject_id', $request->input('subject_id'));
        }

        if ($request->filled('section_id')) {
            $lecturesQuery->where('section_id', $request->input('section_id'));
        }

        if ($request->filled('teacher_id')) {
            $lecturesQuery->where('teacher_id', $request->input('teacher_id'));
        }

        if ($request->filled('type')) {
            $lecturesQuery->where('type', $request->input('type'));
        }

        if ($request->filled('is_published')) {
            $lecturesQuery->where('is_published', $request->input('is_published'));
        }

        $lectures = $lecturesQuery->paginate(15);
        $subjects = Subject::where('is_active', true)->orderBy('name')->get();
        $sections = Section::with('class.grade')->where('is_active', true)->get();
        $teachers = Teacher::with('user')->get();

        return view('admin.pages.online-lectures.index', compact('lectures', 'subjects', 'sections', 'teachers'));
    }

    public function create()
    {
        $subjects = Subject::where('is_active', true)->orderBy('name')->get();
        $sections = Section::with('class.grade')->where('is_active', true)->get();
        $teachers = Teacher::with('user')->get();
        return view('admin.pages.online-lectures.create', compact('subjects', 'sections', 'teachers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'section_id' => 'required|exists:sections,id',
            'teacher_id' => 'required|exists:teachers,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'content' => 'nullable|string',
            'type' => 'required|in:live,recorded,material',
            'video_url' => 'nullable|url',
            'audio_url' => 'nullable|url',
            'scheduled_at' => 'nullable|date',
            'duration' => 'nullable|integer|min:1',
            'meeting_link' => 'nullable|url',
            'meeting_id' => 'nullable|string|max:255',
            'meeting_password' => 'nullable|string|max:255',
            'is_published' => 'boolean',
            'is_active' => 'boolean',
        ]);

        OnlineLecture::create($request->all());

        return redirect()->route('admin.online-lectures.index')->with('success', 'تم إنشاء المحاضرة بنجاح');
    }

    public function show(string $id)
    {
        $lecture = OnlineLecture::with(['subject', 'section.class.grade', 'teacher.user', 'materials', 'attendance.student.user'])->findOrFail($id);
        $lecture->incrementViews();
        return view('admin.pages.online-lectures.show', compact('lecture'));
    }

    public function edit(string $id)
    {
        $lecture = OnlineLecture::findOrFail($id);
        $subjects = Subject::where('is_active', true)->orderBy('name')->get();
        $sections = Section::with('class.grade')->where('is_active', true)->get();
        $teachers = Teacher::with('user')->get();
        return view('admin.pages.online-lectures.edit', compact('lecture', 'subjects', 'sections', 'teachers'));
    }

    public function update(Request $request, string $id)
    {
        $lecture = OnlineLecture::findOrFail($id);

        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'section_id' => 'required|exists:sections,id',
            'teacher_id' => 'required|exists:teachers,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'content' => 'nullable|string',
            'type' => 'required|in:live,recorded,material',
            'video_url' => 'nullable|url',
            'audio_url' => 'nullable|url',
            'scheduled_at' => 'nullable|date',
            'duration' => 'nullable|integer|min:1',
            'meeting_link' => 'nullable|url',
            'meeting_id' => 'nullable|string|max:255',
            'meeting_password' => 'nullable|string|max:255',
            'is_published' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $lecture->update($request->all());

        return redirect()->route('admin.online-lectures.index')->with('success', 'تم تحديث المحاضرة بنجاح');
    }

    public function destroy(string $id)
    {
        $lecture = OnlineLecture::findOrFail($id);
        $lecture->delete();
        return redirect()->route('admin.online-lectures.index')->with('success', 'تم حذف المحاضرة بنجاح');
    }
}
