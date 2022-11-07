<?php

namespace App\Http\Controllers\Admin\Gratifikasi;

use App\Http\Controllers\Controller;
use App\Models\Gratifikasi\Penerima;
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

        $laporan = Penerima::filter($filters)
            ->paginate()->transform(fn ($laporan) => [
                'id' => $laporan->ulid,
                'jenis' => $laporan->jenis?->nama,
                'peristiwa' => $laporan->peristiwa?->nama,
                'pelapor' => [
                    'nama' => $laporan->pelapor?->nama,
                    'nomor_identitas' => $laporan->pelapor?->nomor_identitas,
                    'whatsapp' => $laporan->pelapor?->whatsapp,
                    'email' => $laporan->pelapor?->email,
                    'golongan' => $laporan->pelapor?->golongan,
                    'satker' => $laporan->pelapor?->satker,
                    'unit_tugas' => $laporan->pelapor?->unit_tugas,
                ],
                'pemberi' => [
                    'nama' => $laporan->pemberi?->nama,
                    'pekerjaan' => $laporan->pemberi?->pekerjaan,
                    'jabatan' => $laporan->pemberi?->jabatan,
                    'whatsapp' => $laporan->pemberi?->whatsapp,
                    'email' => $laporan->pemberi?->email,
                    'hubungan' => $laporan->pemberi?->hubungan,
                    'alamat' => $laporan->pemberi?->address?->address,
                ],
                'kronologi' => [
                    'alasan' => $laporan->kronologi?->alasan,
                    'kronologi' => $laporan->kronologi?->kronologi,
                    'catatan' => $laporan->kronologi?->catatan,
                    'lampiran' => $laporan->kronologi?->images->transform(fn ($item) => [
                        'name' => $item->file?->name,
                        'path' => $item->file?->path,
                        'url' => $item->file?->url,
                    ]),
                ],
                'uraian' => $laporan->uraian,
                'nominal' => $laporan->nominal,
                'tempat' => $laporan->tempat,
                'tanggal' => [
                    'time' => $laporan->tanggal,
                    'string' => $laporan->tanggal?->isoFormat('dddd, DD MMMM Y H:mm z'),
                    'short' => $laporan->tanggal?->isoFormat('DD MMMM Y kk:mm'),
                ],
                'created_at' => [
                    'time' => $laporan->created_at,
                    'string' => $laporan->created_at?->isoFormat('dddd, DD MMMM Y H:mm z'),
                    'short' => $laporan->created_at?->isoFormat('DD MMMM Y kk:mm'),
                ],
                'updated_at' => [
                    'time' => $laporan->updated_at,
                    'string' => $laporan->updated_at?->isoFormat('dddd, DD MMMM Y H:mm z'),
                    'short' => $laporan->updated_at?->isoFormat('DD MMMM Y kk:mm'),
                ],
            ]);

        return Inertia::render('Admin/Gratifikasi/Laporan/Index', [
            'filters' => $filters,
            'params' => [
                'title' => 'Laporan Gratifikasi',
            ],
            'laporan' => $laporan,
        ]);
    }

    protected function form(Request $request, Penerima $laporan = null)
    {
        return Inertia::render('Admin/Gratifikasi/Laporan/Form', [
            'laporan' => [
                'id' => $laporan?->ulid,
                'nama' => $laporan?->nama,
                'kode' => $laporan?->kode,
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
                'title' => 'Laporan Gratifikasi',
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

        $permit = Penerima::create($input);

        /* @phpstan-ignore-next-line */
        return redirect()
            ->route('admin.gratifikasi.laporan.index')
            ->banner('Laporan Created');
    }

    public function edit(Penerima $laporan, Request $request)
    {
        return $this->form($request, $laporan);
    }

    public function update(Request $request, Penerima $laporan)
    {
        $input = $request->validate([
            'nama' => ['required'],
            'kode' => ['required'],
        ]);

        $laporan->update($input);

        /* @phpstan-ignore-next-line */
        return redirect()
            ->route('admin.gratifikasi.laporan.index')
            ->banner('Laporan Updated');
    }

    public function destroy(Penerima $laporan, Request $request)
    {
        $laporan->delete();

        /* @phpstan-ignore-next-line */
        return back()
            ->banner('Laporan Deleted');
    }

    public function restore(Penerima $laporan, Request $request)
    {
        $laporan->restore();

        /* @phpstan-ignore-next-line */
        return back()
            ->banner('Laporan Restored');
    }

    public function show(Penerima $laporan, Request $request)
    {
        $laporan = [
            'id' => $laporan->ulid,
            'jenis' => $laporan->jenis?->nama,
            'peristiwa' => $laporan->peristiwa?->nama,
            'pelapor' => [
                'nama' => $laporan->pelapor?->nama,
                'nomor_identitas' => $laporan->pelapor?->nomor_identitas,
                'whatsapp' => $laporan->pelapor?->whatsapp,
                'email' => $laporan->pelapor?->email,
                'golongan' => $laporan->pelapor?->golongan,
                'satker' => $laporan->pelapor?->satker,
                'unit_tugas' => $laporan->pelapor?->unit_tugas,
            ],
            'pemberi' => [
                'nama' => $laporan->pemberi?->nama,
                'pekerjaan' => $laporan->pemberi?->pekerjaan,
                'jabatan' => $laporan->pemberi?->jabatan,
                'whatsapp' => $laporan->pemberi?->whatsapp,
                'email' => $laporan->pemberi?->email,
                'hubungan' => $laporan->pemberi?->hubungan,
                'alamat' => $laporan->pemberi?->address?->address,
            ],
            'kronologi' => [
                'alasan' => $laporan->kronologi?->alasan,
                'kronologi' => $laporan->kronologi?->kronologi,
                'catatan' => $laporan->kronologi?->catatan,
                'lampiran' => $laporan->kronologi?->images->transform(fn ($item) => [
                    'name' => $item->file?->name,
                    'path' => $item->file?->path,
                    'url' => $item->file?->url,
                ]),
            ],
            'uraian' => $laporan->uraian,
            'nominal' => $laporan->nominal,
            'tempat' => $laporan->tempat,
            'tanggal' => [
                'time' => $laporan->tanggal,
                'string' => $laporan->tanggal?->isoFormat('dddd, DD MMMM Y H:mm z'),
                'short' => $laporan->tanggal?->isoFormat('DD MMMM Y kk:mm'),
            ],
            'created_at' => [
                'time' => $laporan->created_at,
                'string' => $laporan->created_at?->isoFormat('dddd, DD MMMM Y H:mm z'),
                'short' => $laporan->created_at?->isoFormat('DD MMMM Y kk:mm'),
            ],
            'updated_at' => [
                'time' => $laporan->updated_at,
                'string' => $laporan->updated_at?->isoFormat('dddd, DD MMMM Y H:mm z'),
                'short' => $laporan->updated_at?->isoFormat('DD MMMM Y kk:mm'),
            ],
        ];

        return Inertia::render('Admin/Gratifikasi/Laporan/Show', [
            'params' => [
                'title' => 'Laporan Gratifikasi',
            ],
            'laporan' => $laporan,
        ]);
    }
}
