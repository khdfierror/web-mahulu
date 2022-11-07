<?php

namespace App\Http\Controllers\Admin\Gratifikasi;

use App\Http\Controllers\Controller;
use App\Models\Gratifikasi\Jenis;
use Illuminate\Http\Request;
use Inertia\Inertia;

class JenisController extends Controller
{
    public function index(Request $request)
    {
        $filters = array_merge([], $request->only([
            'search',
            'trashed',
        ]));

        $jenis = Jenis::filter($filters)
            ->paginate()->transform(fn ($jenis) => [
                'id' => $jenis->ulid,
                'nama' => $jenis->nama,
                'kode' => $jenis->kode,
                'updated_at' => [
                    'time' => $jenis->updated_at,
                    'string' => $jenis->updated_at?->isoFormat('dddd, DD MMMM Y H:mm z'),
                    'short' => $jenis->updated_at?->isoFormat('DD MMMM Y kk:mm'),
                ],
            ]);

        return Inertia::render('Admin/Gratifikasi/Jenis/Index', [
            'filters' => $filters,
            'params' => [
                'title' => 'Jenis Penerimaan Gratifikasi',
            ],
            'jenis' => $jenis,
        ]);
    }

    protected function form(Request $request, Jenis $jenis = null)
    {
        return Inertia::render('Admin/Gratifikasi/Jenis/Form', [
            'jenis' => [
                'id' => $jenis?->ulid,
                'nama' => $jenis?->nama,
                'kode' => $jenis?->kode,
                'created_at' => [
                    'time' => $jenis?->created_at,
                    'string' => $jenis?->created_at?->isoFormat('dddd, DD MMMM Y H:mm z'),
                    'short' => $jenis?->created_at?->isoFormat('DD MMMM Y kk:mm'),
                ],
                'updated_at' => [
                    'time' => $jenis?->updated_at,
                    'string' => $jenis?->updated_at?->isoFormat('dddd, DD MMMM Y H:mm z'),
                    'short' => $jenis?->updated_at?->isoFormat('DD MMMM Y kk:mm'),
                ],
            ],
            'params' => [
                'title' => 'Jenis Penerimaan Gratifikasi',
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
            'kode' => ['required'],
        ]);

        $permit = Jenis::create($input);

        /* @phpstan-ignore-next-line */
        return redirect()
            ->route('admin.gratifikasi.jenis.index')
            ->banner('Jenis Created');
    }

    public function edit(Jenis $jenis, Request $request)
    {
        return $this->form($request, $jenis);
    }

    public function update(Request $request, Jenis $jenis)
    {
        $input = $request->validate([
            'nama' => ['required'],
            'kode' => ['required'],
        ]);

        $jenis->update($input);

        /* @phpstan-ignore-next-line */
        return redirect()
            ->route('admin.gratifikasi.jenis.index')
            ->banner('Jenis Updated');
    }

    public function destroy(Jenis $jenis, Request $request)
    {
        $jenis->delete();

        /* @phpstan-ignore-next-line */
        return back()
            ->banner('Jenis Deleted');
    }

    public function restore(Jenis $jenis, Request $request)
    {
        $jenis->restore();

        /* @phpstan-ignore-next-line */
        return back()
            ->banner('Jenis Restored');
    }
}
