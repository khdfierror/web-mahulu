<?php

namespace App\Http\Controllers\Admin;

use App\Enums\AreaGroup;
use App\Http\Controllers\Controller;
use App\Models\CagarBudaya;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class CagarBudayaController extends Controller
{
    public function index(Request $request)
    {
        $filters = array_merge([], $request->only([
            'search',
            'trashed',
        ]));

        $cagar_budaya = CagarBudaya::filter($filters)
            ->paginate()->transform(fn ($cagar_budaya) => [
                'id' => $cagar_budaya->ulid,
                'name' => $cagar_budaya?->name,
                'cp_name' => $cagar_budaya?->cp_name,
                'cp_phone' => $cagar_budaya?->cp_phone,
                'cp_email' => $cagar_budaya?->cp_email,
                'no_inventaris' => $cagar_budaya?->no_inventaris,
                'periode' => $cagar_budaya?->periode,
                'bahan' => $cagar_budaya?->bahan,
                'ukuran' => $cagar_budaya?->ukuran,
                'keterangan' => $cagar_budaya?->keterangan,
                'created_at' => [
                    'time' => $cagar_budaya?->created_at,
                    'string' => $cagar_budaya?->created_at?->isoFormat('dddd, DD MMMM Y H:mm z'),
                    'short' => $cagar_budaya?->created_at?->isoFormat('DD MMMM Y kk:mm'),
                ],
                'updated_at' => [
                    'time' => $cagar_budaya?->updated_at,
                    'string' => $cagar_budaya?->updated_at?->isoFormat('dddd, DD MMMM Y H:mm z'),
                    'short' => $cagar_budaya?->updated_at?->isoFormat('DD MMMM Y kk:mm'),
                ],
            ]);

        return Inertia::render('Admin/Services/CagarBudaya/Index', [
            'filters' => $filters,
            'cagar_budaya' => $cagar_budaya,
            'params' => [
            ],
        ]);
    }

    protected function form(Request $request, CagarBudaya $cagar_budaya = null)
    {
        // locked to kalimantan
        $provinces = \DB::table('areas')->orderBy('code')->where('group', AreaGroup::Provinsi)->where('code', 'LIKE', '6%')->selectRaw('code id, name label')->get();

        $cities = Inertia::lazy(fn () => \DB::table('areas')->when($request->get('province') ?: $cagar_budaya?->address?->province_code,
            fn ($query, $code) => $query->where('code', 'LIKE', $code.'%')
        )->orderBy('code')->whereIn('group', [AreaGroup::Kabupaten, AreaGroup::Kota])->selectRaw('code id, name label')->get());
        $districts = Inertia::lazy(fn () => \DB::table('areas')->when($request->get('city') ?: $cagar_budaya?->address?->city_code,
            fn ($query, $code) => $query->where('code', 'LIKE', $code.'%')
        )->orderBy('code')->where('group', AreaGroup::Kecamatan)->selectRaw('code id, name label')->get());
        $wards = Inertia::lazy(fn () => \DB::table('areas')->when($request->get('district') ?: $cagar_budaya?->address?->ward_code,
            fn ($query, $code) => $query->where('code', 'LIKE', $code.'%')
        )->orderBy('code')->where('group', AreaGroup::Kelurahan)->selectRaw('code id, name label')->get());

        // fallback
        if (! $request->hasAny(['province', 'city', 'district'])) {
            $cities = \DB::table('areas')->when($cagar_budaya?->address?->province_code ?: '64',
                fn ($query, $code) => $query->where('code', 'LIKE', $code.'%')
            )->orderBy('code')->whereIn('group', [AreaGroup::Kabupaten, AreaGroup::Kota])->selectRaw('code id, name label')->get();
            $districts = \DB::table('areas')->when($cagar_budaya?->address?->city_code ?: '6472',
                fn ($query, $code) => $query->where('code', 'LIKE', $code.'%')
            )->orderBy('code')->where('group', AreaGroup::Kecamatan)->selectRaw('code id, name label')->get();
            $wards = \DB::table('areas')->when($cagar_budaya?->address?->ward_code ?: '6472',
                fn ($query, $code) => $query->where('code', 'LIKE', $code.'%')
            )->orderBy('code')->where('group', AreaGroup::Kelurahan)->selectRaw('code id, name label')->get();
        }

        return Inertia::render('Admin/Services/CagarBudaya/Form', [
            'cagar_budaya' => [
                'id' => $cagar_budaya?->ulid,
                'name' => $cagar_budaya?->name,
                'cp_name' => $cagar_budaya?->cp_name,
                'cp_phone' => $cagar_budaya?->cp_phone,
                'cp_email' => $cagar_budaya?->cp_email,

                'no_inventaris' => $cagar_budaya?->no_inventaris,
                'periode' => $cagar_budaya?->periode,
                'bahan' => $cagar_budaya?->bahan,
                'ukuran' => $cagar_budaya?->ukuran,
                'keterangan' => $cagar_budaya?->keterangan,

                'address' => $cagar_budaya?->address?->address,
                'province_code' => $cagar_budaya?->address?->province_code ?: 64,
                'city_code' => $cagar_budaya?->address?->city_code ?: 6472,
                'district_code' => $cagar_budaya?->address?->district_code,
                'ward_code' => $cagar_budaya?->address?->ward_code,

                'lat' => $cagar_budaya?->address?->lat,
                'lng' => $cagar_budaya?->address?->lng,

                'created_at' => [
                    'time' => $cagar_budaya?->created_at,
                    'string' => $cagar_budaya?->created_at?->isoFormat('dddd, DD MMMM Y H:mm z'),
                    'short' => $cagar_budaya?->created_at?->isoFormat('DD MMMM Y kk:mm'),
                ],
                'updated_at' => [
                    'time' => $cagar_budaya?->updated_at,
                    'string' => $cagar_budaya?->updated_at?->isoFormat('dddd, DD MMMM Y H:mm z'),
                    'short' => $cagar_budaya?->updated_at?->isoFormat('DD MMMM Y kk:mm'),
                ],
            ],
            'cities' => $cities,
            'districts' => $districts,
            'wards' => $wards,
            'params' => [
                'provinces' => $provinces,
            ],
        ]);
    }

    public function create(Request $request)
    {
        return $this->form($request);
    }

    public function store(Request $request)
    {
        $input = $request->validate([
            'name' => ['required'],
            'cp_name' => ['required'],
            'cp_phone' => ['required'],
            'cp_email' => ['required', 'email'],

            'no_inventaris' => ['nullable'],
            'periode' => ['nullable'],
            'bahan' => ['nullable'],
            'ukuran' => ['nullable'],
            'keterangan' => ['nullable'],

            'address' => ['nullable'],
            'province_code' => ['nullable'],
            'city_code' => ['nullable'],
            'district_code' => ['nullable'],
            'ward_code' => ['nullable'],
            'lat' => ['nullable'],
            'lng' => ['nullable'],
        ]);

        DB::transaction(function () use ($input) {
            $cagar_budaya = CagarBudaya::create([
                'name' => $input['name'],
                'cp_name' => $input['cp_name'],
                'cp_phone' => $input['cp_phone'],
                'cp_email' => $input['cp_email'],

                'no_inventaris' => $input['no_inventaris'],
                'periode' => $input['periode'],
                'bahan' => $input['bahan'],
                'ukuran' => $input['ukuran'],
                'keterangan' => $input['keterangan'],
            ]);
            $cagar_budaya->address()->updateOrCreate(
                ['name' => $input['name']],
                [
                    'province_code' => $input['province_code'],
                    'city_code' => $input['city_code'],
                    'district_code' => $input['district_code'],
                    'ward_code' => $input['ward_code'],
                    'address' => $input['address'],

                    'lat' => $input['lat'],
                    'lng' => $input['lng'],
                ]
            );
        });

        /* @phpstan-ignore-next-line */
        return redirect()
            ->route('admin.services.cagar-budaya.index')
            ->banner('Cagar Budaya Created');
    }

    public function edit(CagarBudaya $cagar_budaya, Request $request)
    {
        return $this->form($request, $cagar_budaya);
    }

    public function update(CagarBudaya $cagar_budaya, Request $request)
    {
        $input = $request->validate([
            'name' => ['required'],
            'cp_name' => ['required'],
            'cp_phone' => ['required'],
            'cp_email' => ['required', 'email'],

            'no_inventaris' => ['nullable'],
            'periode' => ['nullable'],
            'bahan' => ['nullable'],
            'ukuran' => ['nullable'],
            'keterangan' => ['nullable'],

            'address' => ['nullable'],
            'province_code' => ['nullable'],
            'city_code' => ['nullable'],
            'district_code' => ['nullable'],
            'ward_code' => ['nullable'],
            'lat' => ['nullable'],
            'lng' => ['nullable'],
        ]);

        DB::transaction(function () use ($input, $cagar_budaya) {
            $cagar_budaya->update([
                'name' => $input['name'],
                'cp_name' => $input['cp_name'],
                'cp_phone' => $input['cp_phone'],
                'cp_email' => $input['cp_email'],
                'no_inventaris' => $input['no_inventaris'],
                'periode' => $input['periode'],
                'bahan' => $input['bahan'],
                'ukuran' => $input['ukuran'],
                'keterangan' => $input['keterangan'],
            ]);

            $cagar_budaya->address()->updateOrCreate(
                ['name' => $input['name']],
                [
                    'province_code' => $input['province_code'],
                    'city_code' => $input['city_code'],
                    'district_code' => $input['district_code'],
                    'ward_code' => $input['ward_code'],
                    'address' => $input['address'],

                    'lat' => $input['lat'],
                    'lng' => $input['lng'],
                ]
            );
        });

        /* @phpstan-ignore-next-line */
        return redirect()
            ->route('admin.services.cagar-budaya.index')
            ->banner('Cagar Budaya Updated');
    }

    public function destroy(CagarBudaya $cagar_budaya, Request $request)
    {
        $cagar_budaya->address()->delete();
        $cagar_budaya->delete();

        /* @phpstan-ignore-next-line */
        return back()
            ->banner('Cagar Budaya Deleted');
    }

    public function restore(CagarBudaya $cagar_budaya, Request $request)
    {
        $cagar_budaya->restore();

        /* @phpstan-ignore-next-line */
        return back()
            ->banner('Cagar Budaya Restored');
    }
}
