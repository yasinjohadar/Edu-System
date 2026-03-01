<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LectureMaterial;
use App\Models\OnlineLecture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LectureMaterialController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:lecture-material-list')->only('index', 'show');
        $this->middleware('permission:lecture-material-create')->only('create', 'store');
        $this->middleware('permission:lecture-material-edit')->only('edit', 'update');
        $this->middleware('permission:lecture-material-delete')->only('destroy');
    }

    public function index(Request $request)
    {
        $materialsQuery = LectureMaterial::with('lecture')->orderBy('sort_order');

        if ($request->filled('lecture_id')) {
            $materialsQuery->where('lecture_id', $request->input('lecture_id'));
        }

        $materials = $materialsQuery->paginate(20);
        $lectures = OnlineLecture::where('is_published', true)->get();

        return view('admin.pages.lecture-materials.index', compact('materials', 'lectures'));
    }

    public function create()
    {
        $lectures = OnlineLecture::where('is_published', true)->get();
        return view('admin.pages.lecture-materials.create', compact('lectures'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'lecture_id' => 'required|exists:online_lectures,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:file,link,video,audio,image',
            'file_path' => 'required_if:type,file,image,audio,video|file|max:10240',
            'external_url' => 'required_if:type,link|url',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $data = $request->except('file_path');

        if ($request->hasFile('file_path')) {
            $file = $request->file('file_path');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $data['file_path'] = $file->storeAs('lectures/materials', $fileName, 'public');
            $data['file_name'] = $file->getClientOriginalName();
            $data['file_size'] = $file->getSize();
            $data['mime_type'] = $file->getMimeType();
        }

        LectureMaterial::create($data);

        return redirect()->route('admin.lecture-materials.index')->with('success', 'تم إنشاء المادة بنجاح');
    }

    public function show(string $id)
    {
        $material = LectureMaterial::with('lecture')->findOrFail($id);
        return view('admin.pages.lecture-materials.show', compact('material'));
    }

    public function edit(string $id)
    {
        $material = LectureMaterial::findOrFail($id);
        $lectures = OnlineLecture::where('is_published', true)->get();
        return view('admin.pages.lecture-materials.edit', compact('material', 'lectures'));
    }

    public function update(Request $request, string $id)
    {
        $material = LectureMaterial::findOrFail($id);

        $request->validate([
            'lecture_id' => 'required|exists:online_lectures,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:file,link,video,audio,image',
            'file_path' => 'nullable|file|max:10240',
            'external_url' => 'required_if:type,link|url',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $data = $request->except('file_path');

        if ($request->hasFile('file_path')) {
            if ($material->file_path) {
                Storage::disk('public')->delete($material->file_path);
            }
            $file = $request->file('file_path');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $data['file_path'] = $file->storeAs('lectures/materials', $fileName, 'public');
            $data['file_name'] = $file->getClientOriginalName();
            $data['file_size'] = $file->getSize();
            $data['mime_type'] = $file->getMimeType();
        }

        $material->update($data);

        return redirect()->route('admin.lecture-materials.index')->with('success', 'تم تحديث المادة بنجاح');
    }

    public function destroy(string $id)
    {
        $material = LectureMaterial::findOrFail($id);

        if ($material->file_path) {
            Storage::disk('public')->delete($material->file_path);
        }

        $material->delete();

        return redirect()->route('admin.lecture-materials.index')->with('success', 'تم حذف المادة بنجاح');
    }
}
