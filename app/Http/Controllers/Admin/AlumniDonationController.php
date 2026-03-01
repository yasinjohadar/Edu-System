<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AlumniDonation;
use Illuminate\Http\Request;

class AlumniDonationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $donations = AlumniDonation::with('alumni')->paginate(15);
        return view('admin.pages.alumni.donations.index', compact('donations'));
    }

    public function create()
    {
        return view('admin.pages.alumni.donations.create');
    }

    public function store(Request $request)
    {
        // Implementation
    }

    public function show(AlumniDonation $alumniDonation)
    {
        return view('admin.pages.alumni.donations.show', compact('alumniDonation'));
    }

    public function edit(AlumniDonation $alumniDonation)
    {
        return view('admin.pages.alumni.donations.edit', compact('alumniDonation'));
    }

    public function update(Request $request, AlumniDonation $alumniDonation)
    {
        // Implementation
    }

    public function destroy(AlumniDonation $alumniDonation)
    {
        // Implementation
    }
}
