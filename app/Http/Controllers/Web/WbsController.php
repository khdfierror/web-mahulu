<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Wbs\Kategori;
use App\Models\Wbs\Laporan;
use App\Models\Wbs\Pelapor;
use App\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Kedeka\Whatsapp\Rules\OnWhatsApp;

class WbsController extends Controller
{
    public function create(Request $request)
    {
        $filters = array_merge([], $request->only([
            'search',
            'trashed',
        ]));

        return Inertia::render('Web/Services/Wbs', [
            'filters' => $filters,
            'params' => [
                'categories' => Kategori::get()->transform(fn ($category) => [
                    'id' => $category->ulid,
                    'label' => $category->nama,
                ]),
            ],
        ]);
    }

    public function store(Request $request)
    {
        $input = $request->validate([
            'kategori' => ['required'],

            'what' => ['required'],
            'where' => ['required'],
            'when' => ['required'],
            'who' => ['required'],
            'how' => ['required'],

            'files' => ['required', 'array', 'min:1'],
            'files.*.file' => ['required', 'file', 'max:2040'],

            'pelapor' => ['required', 'array'],
            'pelapor.nama' => ['required'],
            'pelapor.nomor_identitas' => ['required', 'numeric'],
            'pelapor.whatsapp' => ['required', new OnWhatsApp],
            'pelapor.email' => ['required', 'email'],
        ], [
            'kategori.required' => 'Kategori perlu diisi',

            'what.required' => 'Unsur Aduan (what) perlu diisi',
            'where.required' => 'Unsur Aduan (where) perlu diisi',
            'when.required' => 'Unsur Aduan (when) perlu diisi',
            'who.required' => 'Unsur Aduan (who) perlu diisi',
            'how.required' => 'Unsur Aduan (how) perlu diisi',

            'files.required' => 'Berkas lampiran perlu diisi',

            'pelapor.nama.required' => 'Nama pelapor perlu diisi',
            'pelapor.nomor_identitas.required' => 'NIP/NIK pelapor perlu diisi',
            'pelapor.nomor_identitas.numeric' => 'NIP/NIK harus berupa angka',
            'pelapor.phone.required' => 'Whatsapp pelapor perlu diisi',
            'pelapor.email.required' => 'Email pelapor perlu diisi',
        ], [

        ]);

        try {
            DB::transaction(function () use ($input, $request) {
                $pelapor = Pelapor::firstOrCreate([
                    'whatsapp' => Arr::get($input, 'pelapor.whatsapp'),
                    'nomor_identitas' => Arr::get($input, 'pelapor.nomor_identitas'),
                    'email' => Arr::get($input, 'pelapor.email'),
                ]);

                $pelapor->update([
                    'nama' => Arr::get($input, 'pelapor.nama'),
                ]);

                $kategori = Kategori::where('ulid', $input['kategori'])->first();

                $laporan = Laporan::create([
                    'kategori_id' => $kategori->id,
                    'pelapor_id' => $pelapor->id,

                    'what' => $input['what'],
                    'where' => $input['where'],
                    'when' => $input['when'],
                    'who' => $input['who'],
                    'how' => $input['how'],
                ]);

                foreach ($request->file('files') as $index => $uploaded) {
                    $laporan->uploadImage($uploaded['file'], $index);
                }

                app(Notification::class)->newWbs($request, $laporan);
            });
        } catch (\Throwable $e) {
            Log::error('Error pelaporan WBS', [
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
                'trace' => $e->getTrace(),
            ]);

            throw ValidationException::withMessages([
                'wbs' => 'Terjadi kesalahan, Silahkan coba lagi',
            ]);
        }

        /* @phpstan-ignore-next-line */
        return redirect()
            ->back()
            ->banner('Laporan Anda Telah Diterima');
    }
}
