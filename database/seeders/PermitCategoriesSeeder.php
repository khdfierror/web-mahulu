<?php

namespace Database\Seeders;

use App\Models\PermitCategories;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermitCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::transaction(function () {
            $this->categories();
        });
    }

    public function categories()
    {
        $data = [
            'Pendidikan (tugas sekolah, karya ilmiah)',
            'Ilmu Pengetahuan (penelitian)',
            'Sosial',
            'Keagamaan',
            'Prewedding',
            'Kebudayaan',
            'Pariwisata',
        ];

        foreach ($data as $key => $name) {
            PermitCategories::firstOrCreate([
                'name' => $name,
            ]);
        }
    }
}
