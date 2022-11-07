<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Applicant;
use App\Models\CagarBudaya;
use App\Models\Permit;
use App\Models\PermitCategories;
use App\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class PerizinanController extends Controller
{
    public function create(Request $request)
    {
        $filters = array_merge([], $request->only([
            'search',
            'trashed',
        ]));

        return Inertia::render('Web/Services/Perizinan', [
            'filters' => $filters,
            'params' => [
                'categories' => PermitCategories::get()->transform(fn ($category) => [
                    'id' => $category->ulid,
                    'label' => $category->name,
                ]),
                'cagar_budaya' => CagarBudaya::get()->transform(fn ($cagar_budaya) => [
                    'id' => $cagar_budaya->ulid,
                    'label' => $cagar_budaya->name,
                ]),
            ],
            'permit' => null,
        ]);
    }

    public function store(Request $request)
    {
        $input = $request->validate([
            'category' => ['required'],
            'cagar_budaya' => ['required'],
            'start_at' => ['required', 'date'],
            'end_at' => ['required', 'date'],
            'activity_concept' => ['required'],
            'file' => ['required', 'file', 'max:2040'],
            'applicant' => ['required', 'array'],
            'applicant.name' => ['required'],
            'applicant.agency' => ['required'],
            'applicant.job' => ['required'],
            'applicant.phone' => ['required'],
            'applicant.email' => ['required', 'email'],
            'applicant.image' => ['required', 'file', 'max:2040'],
            'applicant.address' => ['nullable'],
            'applicant.province_code' => ['nullable'],
            'applicant.city_code' => ['nullable'],
            'applicant.district_code' => ['nullable'],
            'applicant.ward_code' => ['nullable'],
        ], [
            'category.required' => 'Kategori izin perlu diisi',
            'cagar_budaya.required' => 'Cagar Budaya perlu diisi',
            'start_at.required' => 'Tanggal mulai perlu diisi',
            'end_at.required' => 'Tanggal selesai perlu diisi',
            'activity_concept.required' => 'Konsep kegiatan perlu diisi',
            'file.required' => 'Berkas lampiran perlu diisi',
            'applicant.name.required' => 'Nama pemohon perlu diisi',
            'applicant.agency.required' => 'Instansi pemohon perlu diisi',
            'applicant.job.required' => 'Pekerjaan pemohon perlu diisi',
            'applicant.phone.required' => 'No telp / whatsapp pemohon perlu diisi',
            'applicant.email.required' => 'Email pemohon perlu diisi',
            'applicant.image.required' => 'Foto identitas pemohon perlu diisi',
        ], [

        ]);

        try {
            DB::transaction(function () use ($input, $request) {
                $applicant = Applicant::firstOrCreate([
                    'phone' => Arr::get($input, 'applicant.phone'),
                    'email' => Arr::get($input, 'applicant.email'),
                ]);
                $applicant->update([
                    'name' => Arr::get($input, 'applicant.name'),
                    'agency' => Arr::get($input, 'applicant.agency'),
                    'job' => Arr::get($input, 'applicant.job'),
                ]);

                $applicant->address()->updateOrCreate(
                    ['name' => Arr::get($input, 'applicant.name')],
                    [
                        'address' => Arr::get($input, 'applicant.address'),
                        'province_code' => Arr::get($input, 'applicant.province_code'),
                        'city_code' => Arr::get($input, 'applicant.city_code'),
                        'district_code' => Arr::get($input, 'applicant.district_code'),
                        'ward_code' => Arr::get($input, 'applicant.ward_code'),
                    ]
                );

                if ($request->hasFile('applicant.image')) {
                    $applicant->updateImage($request->file('applicant.image'));
                }

                $category = PermitCategories::where('ulid', $input['category'])->first();
                $cagar_budaya = CagarBudaya::where('ulid', $input['cagar_budaya'])->first();

                $permit = Permit::create([
                    'start_at' => $input['start_at'],
                    'end_at' => $input['end_at'],
                    'activity_concept' => $input['activity_concept'],
                    'categories_id' => $category->id,
                    'permit_applicant_id' => $applicant->id,
                    'cagar_budaya_id' => $cagar_budaya->id,
                ]);

                if ($request->hasFile('file')) {
                    $permit->updateImage($request->file('file'));
                }

                app(Notification::class)->newPerizinan($request, $permit);
            });
        } catch (\Throwable $e) {
            Log::error('Error izin pemanfaatan cagar budaya', [
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
                'trace' => $e->getTrace(),
            ]);

            throw ValidationException::withMessages([
                'perizinan' => 'Terjadi kesalahan, Silahkan coba lagi',
            ]);
        }

        /* @phpstan-ignore-next-line */
        return redirect()
            ->back()
            ->banner('Permohonan Izin Telah Diajukan');
    }
}
