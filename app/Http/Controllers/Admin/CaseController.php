<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Applicant;
use App\Models\CagarBudaya;
use App\Models\Offense;
use App\Models\PermitCategories;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class CaseController extends Controller
{
    public function index(Request $request)
    {
        $filters = array_merge([], $request->only([
            'search',
            'trashed',
        ]));

        $offense = Offense::filter($filters)
            ->paginate()->transform(fn ($offense) => [
                'id' => $offense->ulid,
                'condition' => $offense?->condition,
                'image' => value(fn ($image) => $image ? [
                    'name' => $image->name,
                    'size' => $image->size,
                    'type' => 'image',
                    'url' => $image->glide_url,
                ] : null, $offense?->image?->file),

                'applicant' => $offense?->applicant,
                'category' => $offense?->category,
                'cagar_budaya' => $offense?->cagar_budaya,
                'reported_at' => [
                    'time' => $offense?->reported_at,
                    'string' => $offense?->reported_at?->isoFormat('dddd, DD MMMM Y H:mm z'),
                    'short' => $offense?->reported_at?->isoFormat('DD MMMM Y kk:mm'),
                ],
                'created_at' => [
                    'time' => $offense?->created_at,
                    'string' => $offense?->created_at?->isoFormat('dddd, DD MMMM Y H:mm z'),
                    'short' => $offense?->created_at?->isoFormat('DD MMMM Y kk:mm'),
                ],
                'updated_at' => [
                    'time' => $offense?->updated_at,
                    'string' => $offense?->updated_at?->isoFormat('dddd, DD MMMM Y H:mm z'),
                    'short' => $offense?->updated_at?->isoFormat('DD MMMM Y kk:mm'),
                ],
            ]);

        return Inertia::render('Admin/Services/Offense/Index', [
            'filters' => $filters,
            'offense' => $offense,
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

    protected function form(Request $request, Offense $offense = null)
    {
        return Inertia::render('Admin/Services/Offense/Form', [
            'offense' => [
                'id' => $offense?->ulid,
                'condition' => $offense?->condition,
                'image' => value(fn ($image) => $image ? [
                    'name' => $image->name,
                    'size' => $image->size,
                    'type' => 'image',
                    'url' => $image->glide_url,
                ] : null, $offense?->image?->file),
                'category' => $offense?->category?->ulid,
                'cagar_budaya' => $offense?->cagar_budaya?->ulid,
                'reported_at' => [
                    'time' => $offense?->reported_at,
                    'string' => $offense?->reported_at?->isoFormat('dddd, DD MMMM Y H:mm z'),
                    'short' => $offense?->reported_at?->isoFormat('DD MMMM Y kk:mm'),
                ],

                'address' => $offense?->address?->address,
                'province_code' => $offense?->address?->province_code,
                'city_code' => $offense?->address?->city_code,
                'district_code' => $offense?->address?->district_code,
                'ward_code' => $offense?->address?->ward_code,

                'applicant' => [
                    'name' => $offense?->applicant?->name,
                    'phone' => $offense?->applicant?->phone,
                    'email' => $offense?->applicant?->email,
                    'job' => $offense?->applicant?->job,
                    'agency' => $offense?->applicant?->agency,
                    'image' => value(fn ($image) => $image ? [
                        'name' => $image->name,
                        'size' => $image->size,
                        'type' => 'image',
                        'url' => $image->glide_url,
                    ] : null, $offense?->applicant?->image?->file),

                    'address' => $offense?->applicant?->address?->address,
                    'province_code' => $offense?->applicant?->address?->province_code,
                    'city_code' => $offense?->applicant?->address?->city_code,
                    'district_code' => $offense?->applicant?->address?->district_code,
                    'ward_code' => $offense?->applicant?->address?->ward_code,
                ],
                'created_at' => [
                    'time' => $offense?->created_at,
                    'string' => $offense?->created_at?->isoFormat('dddd, DD MMMM Y H:mm z'),
                    'short' => $offense?->created_at?->isoFormat('DD MMMM Y kk:mm'),
                ],
                'updated_at' => [
                    'time' => $offense?->updated_at,
                    'string' => $offense?->updated_at?->isoFormat('dddd, DD MMMM Y H:mm z'),
                    'short' => $offense?->updated_at?->isoFormat('DD MMMM Y kk:mm'),
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
            'category' => ['required'],
            'cagar_budaya' => ['required'],
            'condition' => ['required'],
            'image' => ['required', 'file', 'max:2040'],
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

            $category = PermitCategories::where('ulid', $input['category'])->first();
            $cagar_budaya = CagarBudaya::where('ulid', $input['cagar_budaya'])->first();

            $offense = Offense::create([
                'condition' => $input['condition'],
                'reported_at' => now(),
                'categories_id' => $category->id,
                'permit_applicant_id' => $applicant->id,
                'cagar_budaya_id' => $cagar_budaya->id,
            ]);

            if ($request->hasFile('image')) {
                $offense->updateImage($request->file('image'));
            }
        });

        /* @phpstan-ignore-next-line */
        return redirect()
            ->route('admin.services.case.index')
            ->banner('Offense Created');
    }

    public function edit(Offense $offense, Request $request)
    {
        return $this->form($request, $offense);
    }

    public function update(Offense $offense, Request $request)
    {
        $input = $request->validate([
            'category' => ['required'],
            'cagar_budaya' => ['required'],
            'condition' => ['required'],
            'image' => ['max:2040'],
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

        DB::transaction(function () use ($input, $offense, $request) {
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

            $offense->update([
                'condition' => $input['condition'],
                'categories_id' => $category->id,
                'permit_applicant_id' => $applicant->id,
                'cagar_budaya_id' => $cagar_budaya->id,
            ]);
            if ($request->hasFile('file')) {
                $offense->updateImage($request->file('file'));
            }
        });

        /* @phpstan-ignore-next-line */
        return redirect()
            ->route('admin.services.case.index')
            ->banner('Offense Updated');
    }

    public function destroy(Offense $offense, Request $request)
    {
        $offense->delete();

        /* @phpstan-ignore-next-line */
        return back()
            ->banner('Offense Deleted');
    }

    public function restore(Offense $offense, Request $request)
    {
        $offense->restore();

        /* @phpstan-ignore-next-line */
        return back()
            ->banner('Offense Restored');
    }
}
