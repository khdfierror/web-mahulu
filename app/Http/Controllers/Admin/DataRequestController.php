<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Applicant;
use App\Models\CagarBudaya;
use App\Models\DataRequest;
use App\Models\PermitCategories;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DataRequestController extends Controller
{
    public function index(Request $request)
    {
        $filters = array_merge([], $request->only([
            'search',
            'trashed',
        ]));

        $data = DataRequest::filter($filters)
            ->paginate()->transform(fn ($data) => [
                'id' => $data->ulid,
                'name' => $data?->name,
                'concept' => $data?->concept,
                'file' => value(fn ($image) => $image ? [
                    'name' => $image->name,
                    'size' => $image->size,
                    'type' => 'file',
                    'url' => $image->glide_url,
                ] : null, $data?->image?->file),

                'applicant' => $data?->applicant,
                'category' => $data?->category,
                'cagar_budaya' => $data?->cagar_budaya,
                'created_at' => [
                    'time' => $data?->created_at,
                    'string' => $data?->created_at?->isoFormat('dddd, DD MMMM Y H:mm z'),
                    'short' => $data?->created_at?->isoFormat('DD MMMM Y kk:mm'),
                ],
                'updated_at' => [
                    'time' => $data?->updated_at,
                    'string' => $data?->updated_at?->isoFormat('dddd, DD MMMM Y H:mm z'),
                    'short' => $data?->updated_at?->isoFormat('DD MMMM Y kk:mm'),
                ],
            ]);

        return Inertia::render('Admin/Services/Data/Index', [
            'filters' => $filters,
            'data' => $data,
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
        ]);
    }

    protected function form(Request $request, DataRequest $data = null)
    {
        return Inertia::render('Admin/Services/Data/Form', [
            'data' => [
                'id' => $data?->ulid,
                'name' => $data?->name,
                'concept' => $data?->concept,
                'file' => value(fn ($image) => $image ? [
                    'name' => $image->name,
                    'size' => $image->size,
                    'type' => 'file',
                    'url' => $image->glide_url,
                ] : null, $data?->image?->file),
                'category' => $data?->category?->ulid,
                'cagar_budaya' => $data?->cagar_budaya?->ulid,
                'applicant' => [
                    'name' => $data?->applicant?->name,
                    'phone' => $data?->applicant?->phone,
                    'email' => $data?->applicant?->email,
                    'job' => $data?->applicant?->job,
                    'agency' => $data?->applicant?->agency,
                    'image' => value(fn ($image) => $image ? [
                        'name' => $image->name,
                        'size' => $image->size,
                        'type' => 'image',
                        'url' => $image->glide_url,
                    ] : null, $data?->applicant?->image?->file),

                    'address' => $data?->applicant?->address?->address,
                    'province_code' => $data?->applicant?->address?->province_code,
                    'city_code' => $data?->applicant?->address?->city_code,
                    'district_code' => $data?->applicant?->address?->district_code,
                    'ward_code' => $data?->applicant?->address?->ward_code,
                ],
                'created_at' => [
                    'time' => $data?->created_at,
                    'string' => $data?->created_at?->isoFormat('dddd, DD MMMM Y H:mm z'),
                    'short' => $data?->created_at?->isoFormat('DD MMMM Y kk:mm'),
                ],
                'updated_at' => [
                    'time' => $data?->updated_at,
                    'string' => $data?->updated_at?->isoFormat('dddd, DD MMMM Y H:mm z'),
                    'short' => $data?->updated_at?->isoFormat('DD MMMM Y kk:mm'),
                ],
            ],
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
        ]);
    }

    public function create(Request $request)
    {
        return $this->form($request);
    }

    public function store(Request $request)
    {
        $input = $request->validate([
            'cagar_budaya' => ['required'],
            'concept' => ['required'],
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
        ]);

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

            $data = DataRequest::create([
                'concept' => $input['concept'],
                'permit_applicant_id' => $applicant->id,
                'cagar_budaya_id' => $cagar_budaya->id,
            ]);

            if ($request->hasFile('file')) {
                $data->updateImage($request->file('file'));
            }
        });

        /* @phpstan-ignore-next-line */
        return redirect()
            ->route('admin.services.data-request.index')
            ->banner('Data Request Created');
    }

    public function edit(DataRequest $data, Request $request)
    {
        return $this->form($request, $data);
    }

    public function update(DataRequest $data, Request $request)
    {
        $input = $request->validate([
            'cagar_budaya' => ['required'],
            'concept' => ['required'],
            'file' => ['max:2040'],
            'applicant' => ['required', 'array'],
            'applicant.name' => ['required'],
            'applicant.agency' => ['required'],
            'applicant.job' => ['required'],
            'applicant.phone' => ['required'],
            'applicant.email' => ['required', 'email'],
            'applicant.image' => ['max:2040'],
            'applicant.address' => ['nullable'],
            'applicant.province_code' => ['nullable'],
            'applicant.city_code' => ['nullable'],
            'applicant.district_code' => ['nullable'],
            'applicant.ward_code' => ['nullable'],
        ]);

        DB::transaction(function () use ($input, $data, $request) {
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

            $data->update([
                'concept' => $input['concept'],
                'permit_applicant_id' => $applicant->id,
                'cagar_budaya_id' => $cagar_budaya->id,
            ]);
            if ($request->hasFile('file')) {
                $data->updateImage($request->file('file'));
            }
        });

        /* @phpstan-ignore-next-line */
        return redirect()
            ->route('admin.services.data-request.index')
            ->banner('Data Request Updated');
    }

    public function destroy(DataRequest $data, Request $request)
    {
        $data->delete();

        /* @phpstan-ignore-next-line */
        return back()
            ->banner('Data Request Deleted');
    }

    public function restore(DataRequest $data, Request $request)
    {
        $data->restore();

        /* @phpstan-ignore-next-line */
        return back()
            ->banner('Data Request Restored');
    }
}
