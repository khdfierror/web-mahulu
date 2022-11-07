<?php

namespace App\Http\Controllers\Admin\Gratifikasi;

use App\Http\Controllers\Controller;
use App\Models\Gratifikasi\Peristiwa;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PeristiwaController extends Controller
{
    public function index(Request $request)
    {
        $filters = array_merge([], $request->only([
            'search',
            'trashed',
        ]));

        $peristiwa = Peristiwa::filter($filters)
            ->paginate()->transform(fn ($peristiwa) => [
                'id' => $peristiwa->ulid,
                'nama' => $peristiwa->nama,
                'kode' => $peristiwa->kode,
                'updated_at' => [
                    'time' => $peristiwa->updated_at,
                    'string' => $peristiwa->updated_at?->isoFormat('dddd, DD MMMM Y H:mm z'),
                    'short' => $peristiwa->updated_at?->isoFormat('DD MMMM Y kk:mm'),
                ],
            ]);

        return Inertia::render('Admin/Gratifikasi/Peristiwa/Index', [
            'filters' => $filters,
            'params' => [
                'title' => 'Peristiwa Penerimaan Gratifikasi',
            ],
            'peristiwa' => $peristiwa,
        ]);
    }

    protected function form(Request $request, Peristiwa $peristiwa = null)
    {
        return Inertia::render('Admin/Gratifikasi/Peristiwa/Form', [
            'peristiwa' => [
                'id' => $peristiwa?->ulid,
                'nama' => $peristiwa?->nama,
                'kode' => $peristiwa?->kode,
                'created_at' => [
                    'time' => $peristiwa?->created_at,
                    'string' => $peristiwa?->created_at?->isoFormat('dddd, DD MMMM Y H:mm z'),
                    'short' => $peristiwa?->created_at?->isoFormat('DD MMMM Y kk:mm'),
                ],
                'updated_at' => [
                    'time' => $peristiwa?->updated_at,
                    'string' => $peristiwa?->updated_at?->isoFormat('dddd, DD MMMM Y H:mm z'),
                    'short' => $peristiwa?->updated_at?->isoFormat('DD MMMM Y kk:mm'),
                ],
            ],
            'params' => [
                'title' => 'Peristiwa Penerimaan Gratifikasi',
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

        $permit = Peristiwa::create($input);

        /* @phpstan-ignore-next-line */
        return redirect()
            ->route('admin.gratifikasi.peristiwa.index')
            ->banner('Peristiwa Created');
    }

    public function edit(Peristiwa $peristiwa, Request $request)
    {
        return $this->form($request, $peristiwa);
    }

    public function update(Request $request, Peristiwa $peristiwa)
    {
        $input = $request->validate([
            'nama' => ['required'],
            'kode' => ['required'],
        ]);

        $peristiwa->update($input);

        /* @phpstan-ignore-next-line */
        return redirect()
            ->route('admin.gratifikasi.peristiwa.index')
            ->banner('Peristiwa Updated');
    }

    public function destroy(Peristiwa $peristiwa, Request $request)
    {
        $peristiwa->delete();

        /* @phpstan-ignore-next-line */
        return back()
            ->banner('Peristiwa Deleted');
    }

    public function restore(Peristiwa $peristiwa, Request $request)
    {
        $peristiwa->restore();

        /* @phpstan-ignore-next-line */
        return back()
            ->banner('Peristiwa Restored');
    }
}
