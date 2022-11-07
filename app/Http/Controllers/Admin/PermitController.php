<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Applicant;
use App\Models\CagarBudaya;
use App\Models\Permit;
use App\Models\PermitCategories;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class PermitController extends Controller
{
    public function index(Request $request)
    {
        $filters = array_merge([], $request->only([
            'search',
            'trashed',
        ]));

        $permit = Permit::filter($filters)
            ->paginate()->transform(fn ($permit) => [
                'id' => $permit->ulid,
                'name' => $permit?->name,
                'activity_concept' => $permit?->activity_concept,
                'file' => value(fn ($image) => $image ? [
                    'name' => $image->name,
                    'size' => $image->size,
                    'type' => 'file',
                    'url' => $image->glide_url,
                ] : null, $permit?->image?->file),
                'applicant' => $permit?->applicant,
                'category' => $permit?->category,
                'cagar_budaya' => $permit?->cagar_budaya,
                'start_at' => [
                    'time' => $permit?->start_at,
                    'string' => $permit?->start_at?->isoFormat('dddd, DD MMMM Y H:mm z'),
                    'short' => $permit?->start_at?->isoFormat('DD MMMM Y kk:mm'),
                ],
                'end_at' => [
                    'time' => $permit?->end_at,
                    'string' => $permit?->end_at?->isoFormat('dddd, DD MMMM Y H:mm z'),
                    'short' => $permit?->end_at?->isoFormat('DD MMMM Y kk:mm'),
                ],
                'created_at' => [
                    'time' => $permit?->created_at,
                    'string' => $permit?->created_at?->isoFormat('dddd, DD MMMM Y H:mm z'),
                    'short' => $permit?->created_at?->isoFormat('DD MMMM Y kk:mm'),
                ],
                'updated_at' => [
                    'time' => $permit?->updated_at,
                    'string' => $permit?->updated_at?->isoFormat('dddd, DD MMMM Y H:mm z'),
                    'short' => $permit?->updated_at?->isoFormat('DD MMMM Y kk:mm'),
                ],
            ]);

        return Inertia::render('Admin/Services/Permit/Index', [
            'filters' => $filters,
            'permit' => $permit,
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

    protected function form(Request $request, Permit $permit = null)
    {
        return Inertia::render('Admin/Services/Permit/Form', [
            'permit' => [
                'id' => $permit?->ulid,
                'name' => $permit?->name,
                'description' => $permit?->description,
                'activity_concept' => $permit?->activity_concept,
                'file' => value(fn ($image) => $image ? [
                    'name' => $image->name,
                    'size' => $image->size,
                    'type' => 'file',
                    'url' => $image->glide_url,
                ] : null, $permit?->image?->file),
                'category' => $permit?->category?->ulid,
                'cagar_budaya' => $permit?->cagar_budaya?->ulid,
                'applicant' => [
                    'name' => $permit?->applicant?->name,
                    'phone' => $permit?->applicant?->phone,
                    'email' => $permit?->applicant?->email,
                    'job' => $permit?->applicant?->job,
                    'agency' => $permit?->applicant?->agency,
                    'image' => value(fn ($image) => $image ? [
                        'name' => $image->name,
                        'size' => $image->size,
                        'type' => 'image',
                        'url' => $image->glide_url,
                    ] : null, $permit?->applicant?->image?->file),

                    'address' => $permit?->applicant?->address?->address,
                    'province_code' => $permit?->applicant?->address?->province_code,
                    'city_code' => $permit?->applicant?->address?->city_code,
                    'district_code' => $permit?->applicant?->address?->district_code,
                    'ward_code' => $permit?->applicant?->address?->ward_code,
                ],
                'start_at' => $permit?->start_at,
                'end_at' => $permit?->end_at,
                'created_at' => [
                    'time' => $permit?->created_at,
                    'string' => $permit?->created_at?->isoFormat('dddd, DD MMMM Y H:mm z'),
                    'short' => $permit?->created_at?->isoFormat('DD MMMM Y kk:mm'),
                ],
                'updated_at' => [
                    'time' => $permit?->updated_at,
                    'string' => $permit?->updated_at?->isoFormat('dddd, DD MMMM Y H:mm z'),
                    'short' => $permit?->updated_at?->isoFormat('DD MMMM Y kk:mm'),
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
        });

        /* @phpstan-ignore-next-line */
        return redirect()
            ->route('admin.services.permit.index')
            ->banner('Permit Created');
    }

    public function edit(Permit $permit, Request $request)
    {
        return $this->form($request, $permit);
    }

    public function update(Permit $permit, Request $request)
    {
        $input = $request->validate([
            'category' => ['required'],
            'cagar_budaya' => ['required'],
            'start_at' => ['required', 'date'],
            'end_at' => ['required', 'date'],
            'activity_concept' => ['required'],
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

        DB::transaction(function () use ($input, $permit, $request) {
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

            $permit->update([
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
        });

        /* @phpstan-ignore-next-line */
        return redirect()
            ->route('admin.services.permit.index')
            ->banner('Permit Updated');
    }

    public function destroy(Permit $permit, Request $request)
    {
        $permit->delete();

        /* @phpstan-ignore-next-line */
        return back()
            ->banner('Permit Deleted');
    }

    public function restore(Permit $permit, Request $request)
    {
        $permit->restore();

        /* @phpstan-ignore-next-line */
        return back()
            ->banner('Permit Restored');
    }
}
