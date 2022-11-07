<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PermitCategories;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PermitCategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filters = array_merge([], $request->only([
            'search',
            'trashed',
        ]));

        $category = PermitCategories::filter($filters)
            ->paginate()->transform(fn ($category) => [
                'id' => $category->ulid,
                'name' => $category->name,
                'updated_at' => [
                    'time' => $category->updated_at,
                    'string' => $category->updated_at?->isoFormat('dddd, DD MMMM Y H:mm z'),
                    'short' => $category->updated_at?->isoFormat('DD MMMM Y kk:mm'),
                ],
            ]);

        return Inertia::render('Admin/PermitCategories/Index', [
            'filters' => $filters,
            'params' => [

            ],
            'category' => $category,
        ]);
    }

    protected function form(Request $request, PermitCategories $category = null)
    {
        return Inertia::render('Admin/PermitCategories/Form', [
            'category' => [
                'id' => $category?->ulid,
                'name' => $category?->name,
                'created_at' => [
                    'time' => $category?->created_at,
                    'string' => $category?->created_at?->isoFormat('dddd, DD MMMM Y H:mm z'),
                    'short' => $category?->created_at?->isoFormat('DD MMMM Y kk:mm'),
                ],
                'updated_at' => [
                    'time' => $category?->updated_at,
                    'string' => $category?->updated_at?->isoFormat('dddd, DD MMMM Y H:mm z'),
                    'short' => $category?->updated_at?->isoFormat('DD MMMM Y kk:mm'),
                ],
            ],
            'params' => [

            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        return $this->form($request);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->validate([
            'name' => ['required'],
        ]);

        $permit = PermitCategories::create($input);

        /* @phpstan-ignore-next-line */
        return redirect()
            ->route('admin.permit-categories.index')
            ->banner('Category Created');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PermitCategories  $permitCategories
     * @return \Illuminate\Http\Response
     */
    public function edit(PermitCategories $category, Request $request)
    {
        return $this->form($request, $category);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PermitCategories  $permitCategories
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PermitCategories $category)
    {
        $input = $request->validate([
            'name' => ['required'],
        ]);

        $category->update($input);

        /* @phpstan-ignore-next-line */
        return redirect()
            ->route('admin.permit-categories.index')
            ->banner('Permit Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PermitCategories  $permitCategories
     * @return \Illuminate\Http\Response
     */
    public function destroy(PermitCategories $category, Request $request)
    {
        $category->delete();

        /* @phpstan-ignore-next-line */
        return back()
            ->banner('Category Deleted');
    }

    public function restore(PermitCategories $category, Request $request)
    {
        $category->restore();

        /* @phpstan-ignore-next-line */
        return back()
            ->banner('Category Restored');
    }
}
