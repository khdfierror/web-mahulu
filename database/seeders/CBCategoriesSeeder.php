<?php

namespace Database\Seeders;

use App\Models\CBCategories;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CBCategoriesSeeder extends Seeder
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
            'Benda',
            'Bangunan',
            'Struktur',
            'Situs',
            'Kawasan',
        ];

        foreach ($data as $key => $name) {
            CBCategories::firstOrCreate([
                'name' => $name,
            ]);
        }
    }
}
