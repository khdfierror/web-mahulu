<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Applicant;
use App\Models\CagarBudaya;
use App\Models\DataRequest;
use App\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class PermohonanController extends Controller
{
    public function create(Request $request)
    {
        $filters = array_merge([], $request->only([
            'search',
            'trashed',
        ]));

        return Inertia::render('Web/Services/Permohonan', [
            'filters' => $filters,
            'params' => [
                'cagar_budaya' => CagarBudaya::get()->transform(fn ($cagar_budaya) => [
                    'id' => $cagar_budaya->ulid,
                    'label' => $cagar_budaya->name,
                ]),
                'title' => 'Permohonan Data Cagar Budaya',
            ],
        ]);
    }

    public function store(Request $request)
    {
        $input = $request->validate([
            'cagar_budaya' => ['required'],
            'konsep' => ['required'],
            'files' => ['required', 'array', 'min:1'],
            'files.*' => ['required', 'file', 'max:2040'],

            'applicant' => ['required', 'array'],
            'applicant.name' => ['required'],
            'applicant.agency' => ['required'],
            'applicant.job' => ['required'],
            'applicant.phone' => ['required'],
            'applicant.email' => ['required', 'email'],
            'applicant.image' => ['required', 'file', 'max:2040'],
            'applicant.address' => ['required'],
            'applicant.province_code' => ['nullable'],
            'applicant.city_code' => ['nullable'],
            'applicant.district_code' => ['nullable'],
            'applicant.ward_code' => ['nullable'],
        ], [
            'cagar_budaya.required' => 'Cagar Budaya perlu diisi',
            'konsep.required' => 'Konsep penggunaan data perlu diisi',
            'files.required' => 'Berkas lampiran penggunaan data perlu diisi',
            'applicant.name.required' => 'Nama pemohon perlu diisi',
            'applicant.agency.required' => 'Instansi pemohon perlu diisi',
            'applicant.job.required' => 'Pekerjaan pemohon perlu diisi',
            'applicant.phone.required' => 'No telp / whatsapp pemohon perlu diisi',
            'applicant.email.required' => 'Email pemohon perlu diisi',
            'applicant.image.required' => 'Foto identitas pemohon perlu diisi',
            'applicant.address.required' => 'Alamat pemohon perlu diisi',
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

                $cagar_budaya = CagarBudaya::where('ulid', $input['cagar_budaya'])->first();
                $permohonan = DataRequest::create([
                    'concept' => $input['konsep'],

                    'cagar_budaya_id' => $cagar_budaya->id,
                    'permit_applicant_id' => $applicant->id,
                ]);

                foreach ($request->file('files') as $index => $uploaded) {
                    $permohonan->uploadImage($uploaded, $index);
                }

                app(Notification::class)->newPermohonan($request, $permohonan);
            });
        } catch (\Throwable $e) {
            Log::error('Error Permohonan Data Cagar Budaya', [
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
                'trace' => $e->getTrace(),
            ]);

            throw ValidationException::withMessages([
                'permohonan' => 'Terjadi kesalahan, Silahkan coba lagi',
            ]);
        }

        /* @phpstan-ignore-next-line */
        return redirect()
            ->back()
            ->banner('Permohonan data cagar budaya anda telah diterima.');
    }
}
