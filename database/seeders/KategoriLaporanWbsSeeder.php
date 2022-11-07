<?php

namespace Database\Seeders;

use App\Models\Wbs\Kategori;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriLaporanWbsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::transaction(function () {
            $this->kategori();
        });
    }

    public function kategori()
    {
        $data = [
            'Perbuatan melanggar hukum',
            'Perbuatan melanggar kode etik Balai',
            'Perbuatan melanggar kebijakan dan prosedur Balai',
            'Penyalahgunaan jabatan / wewenang',
            'Perbuatan lainnya yang dapat merugikan Balai',
        ];

        foreach ($data as $key => $item) {
            Kategori::firstOrCreate([
                'nama' => $item,
            ]);
        }
    }
}
