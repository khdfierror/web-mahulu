<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Applicant;
use App\Models\CagarBudaya;
use App\Models\CBCategories;
use App\Models\PermitCategories;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $filters = array_merge([], $request->only([
            'search',
            'trashed',
        ]));

        $report = Report::filter($filters)
            ->paginate()->transform(fn ($report) => [
                'id' => $report->ulid,
                'name' => $report?->name,
                'activity_concept' => $report?->activity_concept,
                'image' => value(fn ($image) => $image ? [
                    'name' => $image->name,
                    'size' => $image->size,
                    'type' => 'image',
                    'url' => $image->glide_url,
                ] : null, $report?->image?->file),
                'applicant' => $report?->applicant,
                'category' => $report?->category,
                'cagar_budaya' => $report?->cagar_budaya,
                'start_at' => [
                    'time' => $report?->start_at,
                    'string' => $report?->start_at?->isoFormat('dddd, DD MMMM Y H:mm z'),
                    'short' => $report?->start_at?->isoFormat('DD MMMM Y kk:mm'),
                ],
                'end_at' => [
                    'time' => $report?->end_at,
                    'string' => $report?->end_at?->isoFormat('dddd, DD MMMM Y H:mm z'),
                    'short' => $report?->end_at?->isoFormat('DD MMMM Y kk:mm'),
                ],
                'created_at' => [
                    'time' => $report?->created_at,
                    'string' => $report?->created_at?->isoFormat('dddd, DD MMMM Y H:mm z'),
                    'short' => $report?->created_at?->isoFormat('DD MMMM Y kk:mm'),
                ],
                'updated_at' => [
                    'time' => $report?->updated_at,
                    'string' => $report?->updated_at?->isoFormat('dddd, DD MMMM Y H:mm z'),
                    'short' => $report?->updated_at?->isoFormat('DD MMMM Y kk:mm'),
                ],
            ]);

        return Inertia::render('Admin/Services/Report/Index', [
            'filters' => $filters,
            'report' => $report,
            'params' => [
                'categories' => CBCategories::get()->transform(fn ($category) => [
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

    protected function form(Request $request, Report $report = null)
    {
        return Inertia::render('Admin/Services/Report/Form', [
            'report' => [
                'id' => $report?->ulid,
                'name' => $report?->name,
                'description' => $report?->description,
                'activity_concept' => $report?->activity_concept,
                'image' => value(fn ($image) => $image ? [
                    'name' => $image->name,
                    'size' => $image->size,
                    'type' => 'image',
                    'url' => $image->glide_url,
                ] : null, $report?->image?->file),
                'category' => $report?->category?->ulid,
                'cagar_budaya' => $report?->cagar_budaya?->ulid,
                'applicant' => [
                    'name' => $report?->applicant?->name,
                    'phone' => $report?->applicant?->phone,
                    'email' => $report?->applicant?->email,
                    'job' => $report?->applicant?->job,
                    'agency' => $report?->applicant?->agency,
                    'image' => value(fn ($image) => $image ? [
                        'name' => $image->name,
                        'size' => $image->size,
                        'type' => 'image',
                        'url' => $image->glide_url,
                    ] : null, $report?->applicant?->image?->file),

                    'address' => $report?->applicant?->address?->address,
                    'province_code' => $report?->applicant?->address?->province_code,
                    'city_code' => $report?->applicant?->address?->city_code,
                    'district_code' => $report?->applicant?->address?->district_code,
                    'ward_code' => $report?->applicant?->address?->ward_code,
                ],
                'start_at' => $report?->start_at,
                'end_at' => $report?->end_at,
                'created_at' => [
                    'time' => $report?->created_at,
                    'string' => $report?->created_at?->isoFormat('dddd, DD MMMM Y H:mm z'),
                    'short' => $report?->created_at?->isoFormat('DD MMMM Y kk:mm'),
                ],
                'updated_at' => [
                    'time' => $report?->updated_at,
                    'string' => $report?->updated_at?->isoFormat('dddd, DD MMMM Y H:mm z'),
                    'short' => $report?->updated_at?->isoFormat('DD MMMM Y kk:mm'),
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

            $report = Report::create([
                'start_at' => $input['start_at'],
                'end_at' => $input['end_at'],
                'activity_concept' => $input['activity_concept'],
                'categories_id' => $category->id,
                'permit_applicant_id' => $applicant->id,
                'cagar_budaya_id' => $cagar_budaya->id,
            ]);

            if ($request->hasFile('file')) {
                $report->updateImage($request->file('file'));
            }
        });

        /* @phpstan-ignore-next-line */
        return redirect()
            ->route('admin.services.report.index')
            ->banner('Report Created');
    }

    public function edit(Report $report, Request $request)
    {
        return $this->form($request, $report);
    }

    public function update(Report $report, Request $request)
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

        DB::transaction(function () use ($input, $report, $request) {
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

            $report->update([
                'start_at' => $input['start_at'],
                'end_at' => $input['end_at'],
                'activity_concept' => $input['activity_concept'],
                'categories_id' => $category->id,
                'permit_applicant_id' => $applicant->id,
                'cagar_budaya_id' => $cagar_budaya->id,
            ]);
            if ($request->hasFile('file')) {
                $report->updateImage($request->file('file'));
            }
        });

        /* @phpstan-ignore-next-line */
        return redirect()
            ->route('admin.services.report.index')
            ->banner('Report Updated');
    }

    public function destroy(Report $report, Request $request)
    {
        $report->delete();

        /* @phpstan-ignore-next-line */
        return back()
            ->banner('Report Deleted');
    }

    public function restore(Report $report, Request $request)
    {
        $report->restore();

        /* @phpstan-ignore-next-line */
        return back()
            ->banner('Report Restored');
    }
}
