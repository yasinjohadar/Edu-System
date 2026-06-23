<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\AuthorizesAdminResource;
use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\CertificateTemplate;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CertificateController extends Controller
{
    use AuthorizesAdminResource;

    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeAdminResource('certificate');
    }

    public function index(Request $request)
    {
        $certificates = Certificate::with(['student.user', 'template', 'issuer'])
            ->when($request->filled('type'), fn ($q) => $q->where('type', $request->type))
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = $request->search;
                $q->where(function ($query) use ($search) {
                    $query->where('certificate_number', 'like', "%{$search}%")
                        ->orWhere('verification_code', 'like', "%{$search}%")
                        ->orWhereHas('student.user', fn ($u) => $u->where('name', 'like', "%{$search}%"));
                });
            })
            ->orderByDesc('issue_date')
            ->paginate(15)
            ->withQueryString();

        return view('admin.pages.certificates.index', [
            'certificates' => $certificates,
            'types' => $this->types(),
        ]);
    }

    public function create()
    {
        return view('admin.pages.certificates.create', $this->formData());
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules());
        $notes = $validated['data'] ?? null;
        unset($validated['data']);

        Certificate::create([
            ...$validated,
            'data' => $notes ? ['notes' => $notes] : null,
            'certificate_number' => $this->generateCertificateNumber(),
            'verification_code' => $this->generateVerificationCode(),
            'issued_by' => auth()->id(),
            'is_verified' => (bool) $request->input('is_verified', 0),
        ]);

        return redirect()->route('admin.certificates.index')
            ->with('success', 'تم إصدار الشهادة بنجاح.');
    }

    public function show(Certificate $certificate)
    {
        $certificate->load(['student.user', 'template', 'issuer']);

        return view('admin.pages.certificates.show', [
            'certificate' => $certificate,
            'types' => $this->types(),
        ]);
    }

    public function edit(Certificate $certificate)
    {
        return view('admin.pages.certificates.edit', [
            'certificate' => $certificate,
            ...$this->formData(),
        ]);
    }

    public function update(Request $request, Certificate $certificate)
    {
        $validated = $request->validate($this->rules($certificate->id));
        $notes = $validated['data'] ?? null;
        unset($validated['data']);

        $certificate->update([
            ...$validated,
            'data' => $notes ? ['notes' => $notes] : null,
            'is_verified' => (bool) $request->input('is_verified', 0),
        ]);

        return redirect()->route('admin.certificates.index')
            ->with('success', 'تم تحديث الشهادة بنجاح.');
    }

    public function destroy(Certificate $certificate)
    {
        $certificate->delete();

        return redirect()->route('admin.certificates.index')
            ->with('success', 'تم حذف الشهادة بنجاح.');
    }

    private function formData(): array
    {
        return [
            'types' => $this->types(),
            'templates' => CertificateTemplate::where('is_active', true)->orderBy('name')->get(),
            'students' => Student::with('user')->where('status', 'active')->orderBy('student_code')->get(),
        ];
    }

    private function rules(?int $ignoreId = null): array
    {
        return [
            'template_id' => 'required|exists:certificate_templates,id',
            'student_id' => 'nullable|exists:students,id',
            'type' => ['required', Rule::in(array_keys($this->types()))],
            'issue_date' => 'required|date',
            'data' => 'nullable|string|max:2000',
        ];
    }

    private function types(): array
    {
        return [
            'completion' => 'إتمام',
            'achievement' => 'تميز',
            'attendance' => 'حضور',
            'grade' => 'درجات',
        ];
    }

    private function generateCertificateNumber(): string
    {
        do {
            $number = 'CERT-' . now()->format('Y') . '-' . strtoupper(Str::random(6));
        } while (Certificate::where('certificate_number', $number)->exists());

        return $number;
    }

    private function generateVerificationCode(): string
    {
        do {
            $code = strtoupper(Str::random(12));
        } while (Certificate::where('verification_code', $code)->exists());

        return $code;
    }
}
