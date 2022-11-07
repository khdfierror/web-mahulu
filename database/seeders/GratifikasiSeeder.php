<?php

namespace Database\Seeders;

use App\Models\Gratifikasi\Jenis;
use App\Models\Gratifikasi\Peristiwa;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GratifikasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::transaction(function () {
            $this->jenis();
            $this->peristiwa();
        });
    }

    public function jenis()
    {
        $data = [
            [
                'nama' => 'Uang',
                'kode' => 'A',
            ],
            [
                'nama' => 'Barang',
                'kode' => 'B',
            ],
            [
                'nama' => 'Rabat ( diskon )',
                'kode' => 'C',
            ],
            [
                'nama' => 'Komisi',
                'kode' => 'D',
            ],
            [
                'nama' => 'Pinjaman tanpa bunga',
                'kode' => 'E',
            ],
            [
                'nama' => 'Tiket perjalanan',
                'kode' => 'F',
            ],
            [
                'nama' => 'Fasilitas penginapan',
                'kode' => 'G',
            ],
            [
                'nama' => 'Perjalanan wisata',
                'kode' => 'H',
            ],
            [
                'nama' => 'Pengobatan cuma - cuma',
                'kode' => 'I',
            ],
            [
                'nama' => 'Fasilitas lainnya',
                'kode' => 'J',
            ],
        ];

        foreach ($data as $key => $item) {
            Jenis::firstOrCreate([
                'nama' => $item['nama'],
                'kode' => $item['kode'],
            ]);
        }
    }

    public function peristiwa()
    {
        $data = [
            [
                'nama' => 'Terkait Pernikahan / Keagamaan / Acara Adat',
                'kode' => 'A',
            ],
            [
                'nama' => 'Terkait Mutasi / Promosi / Pisah Sambut',
                'kode' => 'B',
            ],
            [
                'nama' => 'Terkait Tugas Pelayanan',
                'kode' => 'C',
            ],
            [
                'nama' => 'Terkait Tugas Non Pelayanan',
                'kode' => 'D',
            ],
            [
                'nama' => 'Terkait Seminar / Diklat / Workshop',
                'kode' => 'E',
            ],
            [
                'nama' => 'Tidak Tahu',
                'kode' => 'F',
            ],
        ];

        foreach ($data as $key => $item) {
            Peristiwa::firstOrCreate([
                'nama' => $item['nama'],
                'kode' => $item['kode'],
            ]);
        }
    }
}
