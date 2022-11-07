<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Gratifikasi\Jenis;
use App\Models\Gratifikasi\Pelapor;
use App\Models\Gratifikasi\Peristiwa;
use App\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Kedeka\Whatsapp\Rules\OnWhatsApp;

class GratifikasiController extends Controller
{
    public function create(Request $request)
    {
        $filters = array_merge([], $request->only([
            'search',
            'trashed',
        ]));

        return Inertia::render('Web/Services/Gratifikasi', [
            'filters' => $filters,
            'params' => [
                'jenis' => Jenis::get()->transform(fn ($jenis) => [
                    'id' => $jenis->ulid,
                    'label' => $jenis->nama,
                ]),
                'peristiwa' => Peristiwa::get()->transform(fn ($peristiwa) => [
                    'id' => $peristiwa->ulid,
                    'label' => $peristiwa->nama,
                ]),
            ],
        ]);
    }

    public function store(Request $request)
    {
        $input = $request->validate([
            'pelapor_nama' => ['required'],
            'pelapor_nomor_identitas' => ['required', 'numeric'],
            'pelapor_email' => ['required', 'email'],
            'pelapor_whatsapp' => ['required', new OnWhatsApp],
            'pelapor_golongan' => ['nullable'],
            'pelapor_satker' => ['nullable'],
            'pelapor_unit_tugas' => ['nullable'],
            'penerimaan_jenis' => ['required'],
            'penerimaan_uraian' => ['required'],
            'penerimaan_nominal' => ['required', 'numeric'],
            'penerimaan_peristiwa' => ['required'],
            'penerimaan_tanggal' => ['required', 'date'],
            'penerimaan_tempat' => ['required'],
            'pemberi_nama' => ['required'],
            'pemberi_pekerjaan' => ['nullable'],
            'pemberi_jabatan' => ['nullable'],
            'pemberi_alamat' => ['nullable'],
            'pemberi_whatsapp' => ['required'],
            'pemberi_email' => ['required', 'email'],
            'pemberi_hubungan' => ['required'],
            'kronologi_alasan' => ['required'],
            'kronologi_kronologi' => ['required'],
            'kronologi_catatan' => ['required'],
            'kronologi_lampiran' => ['nullable', 'array', 'min:1'],
            // 'kronologi_lampiran.*' => ['file'],
        ], [
            'penerimaan_jenis.required' => 'Jenis penerimaan perlu diisi',
            'penerimaan_peristiwa.required' => 'Peristiwa penerimaan perlu diisi',

            'pelapor_nama.required' => 'Nama pelapor perlu diisi',
            'pelapor_nomor_identitas.required' => 'NIP/NIK pelapor perlu diisi',
            'pelapor_nomor_identitas.numeric' => 'NIP/NIK harus berupa angka',
            'pelapor_whatsapp.required' => 'Whatsapp pelapor perlu diisi',
            'pelapor_email.required' => 'Email pelapor perlu diisi',

            'penerimaan_uraian.required' => 'Uraian penerimaan wajib diisi',
            'penerimaan_nominal.required' => 'Nominal penerimaan wajib diisi',
            'penerimaan_nominal.numeric' => 'Nominal harus berupa angka',
            'penerimaan_tanggal.required' => 'Tanggal penerimaan wajib diisi',
            'penerimaan_tempat.required' => 'Tempat penerimaan wajib diisi',

            'pemberi_nama.required' => 'Nama pemberi wajib diisi',
            'pemberi_whatsapp.required' => 'Nomor Telp / Whatsapp pemberi wajib diisi',
            'pemberi_email.required' => 'Email pemberi wajib diisi',
            'pemberi_hubungan.required' => 'Hubungan pemberi wajib diisi',

            'kronologi_alasan.required' => 'Alasan wajib diisi',
            'kronologi_kronologi.required' => 'Kronologi wajib diisi',
            'kronologi_catatan.required' => 'Catatan wajib diisi',
            // 'kronologi_lampiran.*.max' => 'File lampiran melebihi batas ukuran 2MB',
        ], [

        ]);

        foreach ($input['kronologi_lampiran'] as $key => $value) {
            $size = $value->getSize() / 1024;
            if ($size > 2040) {
                throw ValidationException::withMessages([
                    'kronologi_lampiran' => 'File lampiran melebihi batas ukuran 2MB',
                ]);
            }
        }

        try {
            DB::transaction(function () use ($input, $request) {
                $pelapor = Pelapor::firstOrCreate([
                    'whatsapp' => Arr::get($input, 'pelapor_whatsapp'),
                    'nomor_identitas' => Arr::get($input, 'pelapor_nomor_identitas'),
                    'email' => Arr::get($input, 'pelapor_email'),
                ]);

                $pelapor->update([
                    'nama' => Arr::get($input, 'pelapor_nama'),
                    'golongan' => Arr::get($input, 'pelapor_golongan'),
                    'satker' => Arr::get($input, 'pelapor_satker'),
                    'unit_tugas' => Arr::get($input, 'pelapor_unit_tugas'),
                ]);

                $pemberi = Pemberi::create([
                    'whatsapp' => $input['pemberi_whatsapp'],
                    'email' => $input['pemberi_email'],
                    'nama' => $input['pemberi_nama'],
                    'pekerjaan' => $input['pemberi_pekerjaan'],
                    'jabatan' => $input['pemberi_jabatan'],
                    'hubungan' => $input['pemberi_hubungan'],
                ]);

                $pemberi->address()->updateOrCreate(
                    ['name' => Arr::get($input, 'pemberi_nama')],
                    [
                        'address' => Arr::get($input, 'pemberi_alamat'),
                    ]
                );

                $kronologi = Kronologi::create([
                    'alasan' => $input['kronologi_alasan'],
                    'kronologi' => $input['kronologi_kronologi'],
                    'catatan' => $input['kronologi_catatan'],
                ]);

                foreach ($input['kronologi_lampiran'] as $index => $file) {
                    $kronologi->uploadImage($file, $index);
                }

                $jenis = Jenis::where('ulid', $input['penerimaan_jenis'])->first();
                $peristiwa = Peristiwa::where('ulid', $input['penerimaan_peristiwa'])->first();

                $penerima = Penerima::create([
                    'gratifikasi_jenis_id' => $jenis->id,
                    'gratifikasi_peristiwa_id' => $peristiwa->id,
                    'gratifikasi_pelapor_id' => $pelapor->id,
                    'gratifikasi_pemberi_id' => $pemberi->id,
                    'gratifikasi_kronologi_id' => $kronologi->id,
                    'uraian' => $input['penerimaan_uraian'],
                    'nominal' => $input['penerimaan_nominal'],
                    'tanggal' => $input['penerimaan_tanggal'],
                    'tempat' => $input['penerimaan_tempat'],
                ]);

                app(Notification::class)->newGratifikasi($request, $pelapor, $jenis);
            });
        } catch (\Throwable $e) {
            Log::error('Error pelaporan gratifikasi', [
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
                'trace' => $e->getTrace(),
            ]);

            throw ValidationException::withMessages([
                'gratifikasi' => 'Terjadi kesalahan, Silahkan coba lagi',
            ]);
        }

        /* @phpstan-ignore-next-line */
        return redirect()
            ->back()
            ->banner('Laporan Anda Telah Diterima');
    }
}
