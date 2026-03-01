<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CertificateTemplate;
use Illuminate\Http\Request;

class CertificateTemplateController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $templates = CertificateTemplate::paginate(15);
        return view('admin.pages.certificates.templates.index', compact('templates'));
    }

    public function create()
    {
        return view('admin.pages.certificates.templates.create');
    }

    public function store(Request $request)
    {
        // Implementation
    }

    public function show(CertificateTemplate $certificateTemplate)
    {
        return view('admin.pages.certificates.templates.show', compact('certificateTemplate'));
    }

    public function edit(CertificateTemplate $certificateTemplate)
    {
        return view('admin.pages.certificates.templates.edit', compact('certificateTemplate'));
    }

    public function update(Request $request, CertificateTemplate $certificateTemplate)
    {
        // Implementation
    }

    public function destroy(CertificateTemplate $certificateTemplate)
    {
        // Implementation
    }
}
