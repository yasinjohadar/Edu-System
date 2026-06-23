<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\AuthorizesAdminResource;
use App\Http\Controllers\Controller;
use App\Models\JobPosting;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class JobPostingController extends Controller
{
    use AuthorizesAdminResource;

    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeAdminResource('job-posting');
    }

    public function index(Request $request)
    {
        $jobs = JobPosting::with('poster')
            ->when($request->filled('employment_type'), fn ($q) => $q->where('employment_type', $request->employment_type))
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = $request->search;
                $q->where(function ($query) use ($search) {
                    $query->where('title', 'like', "%{$search}%")
                        ->orWhere('company', 'like', "%{$search}%");
                });
            })
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        return view('admin.pages.alumni.jobs.index', [
            'jobs' => $jobs,
            'employmentTypes' => $this->employmentTypes(),
        ]);
    }

    public function create()
    {
        return view('admin.pages.alumni.jobs.create', [
            'employmentTypes' => $this->employmentTypes(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules());

        JobPosting::create([
            ...$validated,
            'is_active' => (bool) $request->input('is_active', 1),
            'posted_by' => auth()->id(),
        ]);

        return redirect()->route('admin.job-postings.index')
            ->with('success', 'تم نشر الوظيفة بنجاح.');
    }

    public function show(JobPosting $jobPosting)
    {
        $jobPosting->load('poster');

        return view('admin.pages.alumni.jobs.show', [
            'job' => $jobPosting,
            'employmentTypes' => $this->employmentTypes(),
        ]);
    }

    public function edit(JobPosting $jobPosting)
    {
        return view('admin.pages.alumni.jobs.edit', [
            'job' => $jobPosting,
            'employmentTypes' => $this->employmentTypes(),
        ]);
    }

    public function update(Request $request, JobPosting $jobPosting)
    {
        $validated = $request->validate($this->rules());

        $jobPosting->update([
            ...$validated,
            'is_active' => (bool) $request->input('is_active', 0),
        ]);

        return redirect()->route('admin.job-postings.index')
            ->with('success', 'تم تحديث الوظيفة بنجاح.');
    }

    public function destroy(JobPosting $jobPosting)
    {
        $jobPosting->delete();

        return redirect()->route('admin.job-postings.index')
            ->with('success', 'تم حذف الوظيفة بنجاح.');
    }

    private function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:10000',
            'company' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'salary_range' => 'nullable|string|max:100',
            'employment_type' => ['required', Rule::in(array_keys($this->employmentTypes()))],
            'application_deadline' => 'nullable|date',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:20',
        ];
    }

    private function employmentTypes(): array
    {
        return [
            'full_time' => 'دوام كامل',
            'part_time' => 'دوام جزئي',
            'contract' => 'عقد',
            'internship' => 'تدريب',
        ];
    }
}
