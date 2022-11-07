<?php

namespace App\Http\Controllers\Admin\Wbs;

use App\Http\Controllers\Controller;
use App\Models\Wbs\Kategori;
use Illuminate\Http\Request;
use Inertia\Inertia;

class KategoriController extends Controller
{
    public function index(Request $request)
    {
        $filters = array_merge([], $request->only([
            'search',
            'trashed',
        ]));

        $kategori = Kategori::filter($filters)
            ->paginate()->transform(fn ($kategori) => [
                'id' => $kategori->ulid,
                'nama' => $kategori->nama,
                'updated_at' => [
                    'time' => $kategori->updated_at,
                    'string' => $kategori->updated_at?->isoFormat('dddd, DD MMMM Y H:mm z'),
                    'short' => $kategori->updated_at?->isoFormat('DD MMMM Y kk:mm'),
                ],
            ]);

        return Inertia::render('Admin/Wbs/Kategori/Index', [
            'filters' => $filters,
            'params' => [
                'title' => 'Kategori Wbs',
            ],
            'kategori' => $kategori,
        ]);
    }

    protected function form(Request $request, Kategori $kategori = null)
    {
        return Inertia::render('Admin/Wbs/Kategori/Form', [
            'kategori' => [
                'id' => $kategori?->ulid,
                'nama' => $kategori?->nama,
                'created_at' => [
                    'time' => $kategori?->created_at,
                    'string' => $kategori?->created_at?->isoFormat('dddd, DD MMMM Y H:mm z'),
                    'short' => $kategori?->created_at?->isoFormat('DD MMMM Y kk:mm'),
                ],
                'updated_at' => [
                    'time' => $kategori?->updated_at,
                    'string' => $kategori?->updated_at?->isoFormat('dddd, DD MMMM Y H:mm z'),
                    'short' => $kategori?->updated_at?->isoFormat('DD MMMM Y kk:mm'),
                ],
            ],
            'params' => [
                'title' => 'Kategori Wbs',
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
            'nama' => ['required'],
        ]);

        $permit = Kategori::create($input);

        /* @phpstan-ignore-next-line */
        return redirect()
            ->route('admin.wbs.kategori.index')
            ->banner('Category Created');
    }

    public function edit(Kategori $kategori, Request $request)
    {
        return $this->form($request, $kategori);
    }

    public function update(Request $request, Kategori $kategori)
    {
        $input = $request->validate([
            'nama' => ['required'],
        ]);

        $kategori->update($input);

        /* @phpstan-ignore-next-line */
        return redirect()
            ->route('admin.wbs.kategori.index')
            ->banner('Category Updated');
    }

    public function destroy(Kategori $kategori, Request $request)
    {
        $kategori->delete();

        /* @phpstan-ignore-next-line */
        return back()
            ->banner('Category Deleted');
    }

    public function restore(Kategori $kategori, Request $request)
    {
        $kategori->restore();

        /* @phpstan-ignore-next-line */
        return back()
            ->banner('Category Restored');
    }
}
