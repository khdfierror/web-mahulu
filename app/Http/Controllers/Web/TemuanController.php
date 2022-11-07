<?php

namespace App\Http\Controllers\Web;

use App\Enums\AreaGroup;
use App\Http\Controllers\Controller;
use App\Models\Applicant;
use App\Models\CagarBudaya;
use App\Models\CBCategories;
use App\Models\Report;
use App\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class TemuanController extends Controller
{
    public function create(Request $request)
    {
        $filters = array_merge([], $request->only([
            'search',
            'trashed',
        ]));

        // locked to kalimantan
        $provinces = \DB::table('areas')->orderBy('code')->where('group', AreaGroup::Provinsi)->where('code', 'LIKE', '6%')->selectRaw('code id, name label')->get();

        $cities = Inertia::lazy(fn () => \DB::table('areas')->when($request->get('province'),
            fn ($query, $code) => $query->where('code', 'LIKE', $code.'%')
        )->orderBy('code')->whereIn('group', [AreaGroup::Kabupaten, AreaGroup::Kota])->selectRaw('code id, name label')->get());
        $districts = Inertia::lazy(fn () => \DB::table('areas')->when($request->get('city'),
            fn ($query, $code) => $query->where('code', 'LIKE', $code.'%')
        )->orderBy('code')->where('group', AreaGroup::Kecamatan)->selectRaw('code id, name label')->get());
        $wards = Inertia::lazy(fn () => \DB::table('areas')->when($request->get('district'),
            fn ($query, $code) => $query->where('code', 'LIKE', $code.'%')
        )->orderBy('code')->where('group', AreaGroup::Kelurahan)->selectRaw('code id, name label')->get());

        // fallback
        if (! $request->hasAny(['province', 'city', 'district'])) {
            $cities = null;
            $districts = null;
            $wards = null;
        }

        return Inertia::render('Web/Services/Temuan', [
            'filters' => $filters,
            'params' => [
                'categories' => CBCategories::get()->transform(fn ($category) => [
                    'id' => $category->ulid,
                    'label' => $category->name,
                ]),
                'cagar_budaya' => CagarBudaya::get()->transform(fn ($cagar_budaya) => [
                    'id' => $cagar_budaya->ulid,
                    'label' => $cagar_budaya->name,
                ]),
                'provinces' => $provinces,
            ],
            'cities' => $cities,
            'districts' => $districts,
            'wards' => $wards,
        ]);
    }

    public function store(Request $request)
    {
        $input = $request->validate([
            'name' => ['required'],
            'category' => ['required'],
            'condition' => ['required'],
            'additional_information' => ['nullable'],
            'discovered_at' => ['required', 'date'],
            'address' => ['required'],
            'province_code' => ['nullable'],
            'city_code' => ['nullable'],
            'district_code' => ['nullable'],
            'ward_code' => ['nullable'],

            'files' => ['required', 'array', 'min:1'],
            'files.*.file' => ['required', 'file', 'max:2040'],

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
            'name.required' => 'Nama objek perlu diisi',
            'category.required' => 'Kategori izin perlu diisi',
            'condition.required' => 'Kondisi objek perlu diisi',
            'discovered_at.required' => 'Tanggal ditemukan perlu diisi',
            'address.required' => 'Alamat perlu diisi',
            'files.required' => 'Berkas lampiran perlu diisi',
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

                $category = CBCategories::where('ulid', $input['category'])->first();
                $temuan = Report::create([
                    'name' => $input['name'],
                    'condition' => $input['condition'],
                    'additional_information' => $input['additional_information'],
                    'discovery_date' => $input['discovered_at'],

                    'categories_id' => $category->id,
                    'permit_applicant_id' => $applicant->id,
                ]);

                $temuan->address()->updateOrCreate(
                    ['name' => Arr::get($input, 'name')],
                    [
                        'address' => Arr::get($input, 'address'),
                        'province_code' => Arr::get($input, 'province_code'),
                        'city_code' => Arr::get($input, 'city_code'),
                        'district_code' => Arr::get($input, 'district_code'),
                        'ward_code' => Arr::get($input, 'ward_code'),
                    ]
                );

                foreach ($request->file('files') as $index => $uploaded) {
                    $temuan->uploadImage($uploaded['file'], $index);
                }

                app(Notification::class)->newTemuan($request, $temuan);
            });
        } catch (\Throwable $e) {
            Log::error('Error pelaporan temuan ODBC', [
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
                'trace' => $e->getTrace(),
            ]);

            throw ValidationException::withMessages([
                'temuan' => 'Terjadi kesalahan, Silahkan coba lagi',
            ]);
        }

        /* @phpstan-ignore-next-line */
        return redirect()
            ->back()
            ->banner('Laporan Anda Telah Diterima');
    }
}
