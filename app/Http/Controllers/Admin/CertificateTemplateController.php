<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\AuthorizesAdminResource;
use App\Http\Controllers\Controller;
use App\Models\CertificateTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class CertificateTemplateController extends Controller
{
    use AuthorizesAdminResource;

    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeAdminResource('certificate-template');
    }

    public function index()
    {
        $templates = CertificateTemplate::with('creator')
            ->withCount('certificates')
            ->orderBy('name')
            ->paginate(15);

        return view('admin.pages.certificates.templates.index', [
            'templates' => $templates,
            'types' => $this->types(),
        ]);
    }

    public function create()
    {
        return view('admin.pages.certificates.templates.create', [
            'types' => $this->types(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules());
        $fields = isset($validated['fields']) ? json_decode($validated['fields'], true) : null;
        unset($validated['fields']);

        if ($request->hasFile('background_image')) {
            $validated['background_image'] = $request->file('background_image')
                ->store('certificate-templates', 'public');
        }

        CertificateTemplate::create([
            ...$validated,
            'fields' => $fields,
            'is_active' => (bool) $request->input('is_active', 1),
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('admin.certificate-templates.index')
            ->with('success', 'تم إنشاء قالب الشهادة بنجاح.');
    }

    public function show(CertificateTemplate $certificateTemplate)
    {
        $certificateTemplate->load(['creator', 'certificates']);

        return view('admin.pages.certificates.templates.show', [
            'template' => $certificateTemplate,
            'types' => $this->types(),
        ]);
    }

    public function edit(CertificateTemplate $certificateTemplate)
    {
        return view('admin.pages.certificates.templates.edit', [
            'template' => $certificateTemplate,
            'types' => $this->types(),
        ]);
    }

    public function update(Request $request, CertificateTemplate $certificateTemplate)
    {
        $validated = $request->validate($this->rules($certificateTemplate->id));
        $fields = array_key_exists('fields', $validated)
            ? ($validated['fields'] ? json_decode($validated['fields'], true) : null)
            : $certificateTemplate->fields;
        unset($validated['fields']);

        if ($request->hasFile('background_image')) {
            if ($certificateTemplate->background_image) {
                Storage::disk('public')->delete($certificateTemplate->background_image);
            }
            $validated['background_image'] = $request->file('background_image')
                ->store('certificate-templates', 'public');
        }

        $certificateTemplate->update([
            ...$validated,
            'fields' => $fields,
            'is_active' => (bool) $request->input('is_active', 0),
        ]);

        return redirect()->route('admin.certificate-templates.index')
            ->with('success', 'تم تحديث قالب الشهادة بنجاح.');
    }

    public function destroy(CertificateTemplate $certificateTemplate)
    {
        if ($certificateTemplate->certificates()->exists()) {
            return redirect()->route('admin.certificate-templates.index')
                ->with('error', 'لا يمكن حذف القالب لأنه مرتبط بشهادات صادرة.');
        }

        if ($certificateTemplate->background_image) {
            Storage::disk('public')->delete($certificateTemplate->background_image);
        }

        $certificateTemplate->delete();

        return redirect()->route('admin.certificate-templates.index')
            ->with('success', 'تم حذف قالب الشهادة بنجاح.');
    }

    private function rules(?int $ignoreId = null): array
    {
        return [
            'name' => 'required|string|max:255',
            'type' => ['required', Rule::in(array_keys($this->types()))],
            'html_template' => 'required|string',
            'fields' => 'nullable|json',
            'background_image' => 'nullable|image|max:4096',
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
}
