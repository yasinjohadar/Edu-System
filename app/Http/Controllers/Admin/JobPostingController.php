<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobPosting;
use Illuminate\Http\Request;

class JobPostingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $jobs = JobPosting::with('poster')->paginate(15);
        return view('admin.pages.alumni.jobs.index', compact('jobs'));
    }

    public function create()
    {
        return view('admin.pages.alumni.jobs.create');
    }

    public function store(Request $request)
    {
        // Implementation
    }

    public function show(JobPosting $jobPosting)
    {
        return view('admin.pages.alumni.jobs.show', compact('jobPosting'));
    }

    public function edit(JobPosting $jobPosting)
    {
        return view('admin.pages.alumni.jobs.edit', compact('jobPosting'));
    }

    public function update(Request $request, JobPosting $jobPosting)
    {
        // Implementation
    }

    public function destroy(JobPosting $jobPosting)
    {
        // Implementation
    }
}
