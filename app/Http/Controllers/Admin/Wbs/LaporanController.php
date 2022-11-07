<?php

namespace App\Http\Controllers\Admin\Wbs;

use App\Http\Controllers\Controller;
use App\Models\Wbs\Laporan;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $filters = array_merge([], $request->only([
            'search',
            'trashed',
        ]));

        $laporan = Laporan::with('pelapor', 'kategori')->filter($filters)
            ->paginate()->transform(fn ($laporan) => [
                'id' => $laporan->ulid,
                'what' => $laporan->what,
                'where' => $laporan->where,
                'when' => $laporan->when,
                'who' => $laporan->who,
                'how' => $laporan->how,
                'pelapor' => [
                    'nama' => $laporan->pelapor?->nama,
                    'nomor_identitas' => $laporan->pelapor?->nomor_identitas,
                    'email' => $laporan->pelapor?->email,
                    'whatsapp' => $laporan->pelapor?->whatsapp,
                ],
                'kategori' => [
                    'id' => $laporan?->kategori?->uuid,
                    'nama' => $laporan?->kategori?->nama,
                ],
                'updated_at' => [
                    'time' => $laporan->updated_at,
                    'string' => $laporan->updated_at?->isoFormat('dddd, DD MMMM Y H:mm z'),
                    'short' => $laporan->updated_at?->isoFormat('DD MMMM Y kk:mm'),
                ],
            ]);

        return Inertia::render('Admin/Wbs/Laporan/Index', [
            'filters' => $filters,
            'params' => [
                'title' => 'Laporan Wbs',
            ],
            'laporan' => $laporan,
        ]);
    }

    protected function form(Request $request, Laporan $laporan = null)
    {
        return Inertia::render('Admin/Wbs/Laporan/Form', [
            'laporan' => [
                'id' => $laporan?->ulid,
                'nama' => $laporan?->nama,
                'created_at' => [
                    'time' => $laporan?->created_at,
                    'string' => $laporan?->created_at?->isoFormat('dddd, DD MMMM Y H:mm z'),
                    'short' => $laporan?->created_at?->isoFormat('DD MMMM Y kk:mm'),
                ],
                'updated_at' => [
                    'time' => $laporan?->updated_at,
                    'string' => $laporan?->updated_at?->isoFormat('dddd, DD MMMM Y H:mm z'),
                    'short' => $laporan?->updated_at?->isoFormat('DD MMMM Y kk:mm'),
                ],
            ],
            'params' => [
                'title' => 'Laporan Wbs',
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

        $permit = Laporan::create($input);

        /* @phpstan-ignore-next-line */
        return redirect()
            ->route('admin.wbs.laporan.index')
            ->banner('Laporan Created');
    }

    public function edit(Laporan $laporan, Request $request)
    {
        return $this->form($request, $laporan);
    }

    public function update(Request $request, Laporan $laporan)
    {
        $input = $request->validate([
            'nama' => ['required'],
        ]);

        $laporan->update($input);

        /* @phpstan-ignore-next-line */
        return redirect()
            ->route('admin.wbs.laporan.index')
            ->banner('Laporan Updated');
    }

    public function destroy(Laporan $laporan, Request $request)
    {
        $laporan->delete();

        /* @phpstan-ignore-next-line */
        return back()
            ->banner('Laporan Deleted');
    }

    public function restore(Laporan $laporan, Request $request)
    {
        $laporan->restore();

        /* @phpstan-ignore-next-line */
        return back()
            ->banner('Laporan Restored');
    }
}
