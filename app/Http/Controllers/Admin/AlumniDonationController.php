<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\AuthorizesAdminResource;
use App\Http\Controllers\Controller;
use App\Models\Alumni;
use App\Models\AlumniDonation;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AlumniDonationController extends Controller
{
    use AuthorizesAdminResource;

    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeAdminResource('alumni-donation');
    }

    public function index(Request $request)
    {
        $donations = AlumniDonation::with('alumni')
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->when($request->filled('alumni_id'), fn ($q) => $q->where('alumni_id', $request->alumni_id))
            ->orderByDesc('donation_date')
            ->paginate(15)
            ->withQueryString();

        return view('admin.pages.alumni.donations.index', [
            'donations' => $donations,
            'statuses' => $this->statuses(),
            'paymentMethods' => $this->paymentMethods(),
            'alumniList' => Alumni::where('is_active', true)->orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function create()
    {
        return view('admin.pages.alumni.donations.create', $this->formData());
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules());

        $reference = $validated['reference_number'] ?? $this->generateReference();
        unset($validated['reference_number']);

        AlumniDonation::create([
            ...$validated,
            'reference_number' => $reference,
        ]);

        return redirect()->route('admin.alumni-donations.index')
            ->with('success', 'تم تسجيل التبرع بنجاح.');
    }

    public function show(AlumniDonation $alumniDonation)
    {
        $alumniDonation->load('alumni');

        return view('admin.pages.alumni.donations.show', [
            'donation' => $alumniDonation,
            'statuses' => $this->statuses(),
            'paymentMethods' => $this->paymentMethods(),
        ]);
    }

    public function edit(AlumniDonation $alumniDonation)
    {
        return view('admin.pages.alumni.donations.edit', [
            'donation' => $alumniDonation,
            ...$this->formData(),
        ]);
    }

    public function update(Request $request, AlumniDonation $alumniDonation)
    {
        $validated = $request->validate($this->rules());

        $alumniDonation->update($validated);

        return redirect()->route('admin.alumni-donations.index')
            ->with('success', 'تم تحديث التبرع بنجاح.');
    }

    public function destroy(AlumniDonation $alumniDonation)
    {
        $alumniDonation->delete();

        return redirect()->route('admin.alumni-donations.index')
            ->with('success', 'تم حذف سجل التبرع بنجاح.');
    }

    private function formData(): array
    {
        return [
            'alumniList' => Alumni::where('is_active', true)->orderBy('name')->get(),
            'statuses' => $this->statuses(),
            'paymentMethods' => $this->paymentMethods(),
        ];
    }

    private function rules(): array
    {
        return [
            'alumni_id' => 'required|exists:alumni,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => ['required', Rule::in(array_keys($this->paymentMethods()))],
            'donation_date' => 'required|date',
            'purpose' => 'nullable|string|max:1000',
            'status' => ['required', Rule::in(array_keys($this->statuses()))],
            'reference_number' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:2000',
        ];
    }

    private function statuses(): array
    {
        return [
            'pending' => 'قيد الانتظار',
            'completed' => 'مكتمل',
            'failed' => 'فاشل',
        ];
    }

    private function paymentMethods(): array
    {
        return [
            'cash' => 'نقداً',
            'bank_transfer' => 'تحويل بنكي',
            'card' => 'بطاقة',
            'online' => 'إلكتروني',
            'other' => 'أخرى',
        ];
    }

    private function generateReference(): string
    {
        do {
            $ref = 'DON-' . now()->format('Ymd') . '-' . strtoupper(Str::random(4));
        } while (AlumniDonation::where('reference_number', $ref)->exists());

        return $ref;
    }
}
