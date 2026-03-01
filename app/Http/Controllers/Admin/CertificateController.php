<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use Illuminate\Http\Request;

class CertificateController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $certificates = Certificate::with(['student.user', 'template'])->paginate(15);
        return view('admin.pages.certificates.index', compact('certificates'));
    }

    public function create()
    {
        return view('admin.pages.certificates.create');
    }

    public function store(Request $request)
    {
        // Implementation
    }

    public function show(Certificate $certificate)
    {
        return view('admin.pages.certificates.show', compact('certificate'));
    }

    public function edit(Certificate $certificate)
    {
        return view('admin.pages.certificates.edit', compact('certificate'));
    }

    public function update(Request $request, Certificate $certificate)
    {
        // Implementation
    }

    public function destroy(Certificate $certificate)
    {
        // Implementation
    }
}
