<?php

namespace Database\Seeders;

use App\Models\Area;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class AreasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $truncate = $this->ask_value();

        if ($truncate == 'y') {
            Area::truncate();
        }

        DB::transaction(function () {
            $this->areas();
        });
    }

    public function areas()
    {
        $data = [
            ['group' => 'kecamatan', 'code' => '6472010', 'name' => 'Palaran'],
            ['group' => 'kelurahan', 'code' => '6472010001', 'name' => 'Simpang Pasir'],
            ['group' => 'kelurahan', 'code' => '6472010002', 'name' => 'Handil Bakti'],
            ['group' => 'kelurahan', 'code' => '6472010003', 'name' => 'Bantuas'],
            ['group' => 'kelurahan', 'code' => '6472010004', 'name' => 'Bukuan'],
            ['group' => 'kelurahan', 'code' => '6472010005', 'name' => 'Rawa Makmur'],
            ['group' => 'kecamatan', 'code' => '6472020', 'name' => 'Samarinda Ilir'],
            ['group' => 'kelurahan', 'code' => '6472020006', 'name' => 'Selili'],
            ['group' => 'kelurahan', 'code' => '6472020007', 'name' => 'Sungai Dama'],
            ['group' => 'kelurahan', 'code' => '6472020008', 'name' => 'Sidodamai'],
            ['group' => 'kelurahan', 'code' => '6472020009', 'name' => 'Sidomulyo'],
            ['group' => 'kelurahan', 'code' => '6472020014', 'name' => 'Pelita'],
            ['group' => 'kecamatan', 'code' => '6472021', 'name' => 'Samarinda Kota'],
            ['group' => 'kelurahan', 'code' => '6472021001', 'name' => 'Bugis'],
            ['group' => 'kelurahan', 'code' => '6472021002', 'name' => 'Pasar Pagi'],
            ['group' => 'kelurahan', 'code' => '6472021003', 'name' => 'Pelabuhan'],
            ['group' => 'kelurahan', 'code' => '6472021004', 'name' => 'Sungai Pinang Luar'],
            ['group' => 'kelurahan', 'code' => '6472021005', 'name' => 'Karang Mumus'],
            ['group' => 'kecamatan', 'code' => '6472022', 'name' => 'Sambutan'],
            ['group' => 'kelurahan', 'code' => '6472022001', 'name' => 'Pulau Atas'],
            ['group' => 'kelurahan', 'code' => '6472022002', 'name' => 'Sindang Sari'],
            ['group' => 'kelurahan', 'code' => '6472022003', 'name' => 'Makroman'],
            ['group' => 'kelurahan', 'code' => '6472022004', 'name' => 'Sambutan'],
            ['group' => 'kelurahan', 'code' => '6472022005', 'name' => 'Sungai Kapih'],
            ['group' => 'kecamatan', 'code' => '6472030', 'name' => 'Samarinda Seberang'],
            ['group' => 'kelurahan', 'code' => '6472030001', 'name' => 'Mesjid'],
            ['group' => 'kelurahan', 'code' => '6472030002', 'name' => 'Baqa'],
            ['group' => 'kelurahan', 'code' => '6472030005', 'name' => 'Sungai Keledang'],
            ['group' => 'kelurahan', 'code' => '6472030006', 'name' => 'Mangkupalas'],
            ['group' => 'kelurahan', 'code' => '6472030007', 'name' => 'Tenun Samarinda'],
            ['group' => 'kelurahan', 'code' => '6472030008', 'name' => 'Gunung Panjang'],
            ['group' => 'kecamatan', 'code' => '6472031', 'name' => 'Loa Janan Ilir'],
            ['group' => 'kelurahan', 'code' => '6472031001', 'name' => 'Sengkotek'],
            ['group' => 'kelurahan', 'code' => '6472031002', 'name' => 'Simpang Tiga'],
            ['group' => 'kelurahan', 'code' => '6472031003', 'name' => 'Tani Aman'],
            ['group' => 'kelurahan', 'code' => '6472031004', 'name' => 'Harapan Baru'],
            ['group' => 'kelurahan', 'code' => '6472031005', 'name' => 'Rapak Dalam'],
            ['group' => 'kecamatan', 'code' => '6472040', 'name' => 'Sungai Kunjang'],
            ['group' => 'kelurahan', 'code' => '6472040001', 'name' => 'Loa Buah'],
            ['group' => 'kelurahan', 'code' => '6472040002', 'name' => 'Loa Bakung'],
            ['group' => 'kelurahan', 'code' => '6472040003', 'name' => 'Karang Asam Ulu'],
            ['group' => 'kelurahan', 'code' => '6472040004', 'name' => 'Teluk Lerong Ulu'],
            ['group' => 'kelurahan', 'code' => '6472040005', 'name' => 'Lok Bahu'],
            ['group' => 'kelurahan', 'code' => '6472040006', 'name' => 'Karang Asam Ilir'],
            ['group' => 'kelurahan', 'code' => '6472040007', 'name' => 'Karang Anyar'],
            ['group' => 'kecamatan', 'code' => '6472050', 'name' => 'Samarinda Ulu'],
            ['group' => 'kelurahan', 'code' => '6472050001', 'name' => 'Teluk Lerong Ilir'],
            ['group' => 'kelurahan', 'code' => '6472050002', 'name' => 'Jawa'],
            ['group' => 'kelurahan', 'code' => '6472050004', 'name' => 'Dadi Mulya'],
            ['group' => 'kelurahan', 'code' => '6472050005', 'name' => 'Sidodadi'],
            ['group' => 'kelurahan', 'code' => '6472050006', 'name' => 'Gunung Kelua'],
            ['group' => 'kelurahan', 'code' => '6472050007', 'name' => 'Air Hitam'],
            ['group' => 'kelurahan', 'code' => '6472050008', 'name' => 'Air Putih'],
            ['group' => 'kelurahan', 'code' => '6472050009', 'name' => 'Bukit Pinang'],
            ['group' => 'kecamatan', 'code' => '6472060', 'name' => 'Samarinda Utara'],
            ['group' => 'kelurahan', 'code' => '6472060004', 'name' => 'Lempake'],
            ['group' => 'kelurahan', 'code' => '6472060005', 'name' => 'Sempaja Selatan'],
            ['group' => 'kelurahan', 'code' => '6472060006', 'name' => 'Sungai Siring'],
            ['group' => 'kelurahan', 'code' => '6472060010', 'name' => 'Tanah Merah'],
            ['group' => 'kelurahan', 'code' => '6472060011', 'name' => 'Sempaja Utara'],
            ['group' => 'kelurahan', 'code' => '6472060012', 'name' => 'Sempaja Timur'],
            ['group' => 'kelurahan', 'code' => '6472060013', 'name' => 'Sempaja Barat'],
            ['group' => 'kelurahan', 'code' => '6472060014', 'name' => 'Budaya Pampang'],
            ['group' => 'kecamatan', 'code' => '6472061', 'name' => 'Sungai Pinang'],
            ['group' => 'kelurahan', 'code' => '6472061001', 'name' => 'Temindung Permai'],
            ['group' => 'kelurahan', 'code' => '6472061002', 'name' => 'Bandara'],
            ['group' => 'kelurahan', 'code' => '6472061003', 'name' => 'Sungai Pinang Dalam'],
            ['group' => 'kelurahan', 'code' => '6472061004', 'name' => 'Mugirejo'],
            ['group' => 'kelurahan', 'code' => '6472061005', 'name' => 'Gunung Lingai'],
        ];

        foreach ($data as $item) {
            Area::firstOrCreate($item);
            $this->command->getOutput()->writeln('Area <info>'.$item['name'].'</info> Added');
        }

        // base params, {level, parent}
        $base_url = 'https://sig.bps.go.id/rest-bridging/getwilayah';
        $list_provinsi = collect(json_decode(Http::timeout(60)->get($base_url, ['level' => 'provinsi', 'parent' => ''])->body()));
        $list_kabkota = collect(json_decode(Http::timeout(60)->get($base_url, ['level' => 'kabupaten', 'parent' => ''])->body()));
        $list_kabupaten = $list_kabkota->filter(fn ($item) => Str::startsWith($item->nama_dagri, 'KAB'))->flatten();
        $list_kota = $list_kabkota->filter(fn ($item) => ! Str::startsWith($item->nama_dagri, 'KAB'))->flatten();
        $list_kecamatan = collect(json_decode(Http::timeout(60)->get($base_url, ['level' => 'kecamatan', 'parent' => ''])->body()));
        $list_kelurahan = collect(json_decode(Http::timeout(60)->get($base_url, ['level' => 'desa', 'parent' => ''])->body()))->where('kode_dagri', '<>', '-');

        foreach ($list_provinsi as $provinsi) {
            Area::firstOrCreate([
                'group' => 'provinsi',
                'code' => str_replace('.', '', $provinsi->kode_bps),
            ])->update(['name' => ucwords(strtolower($provinsi->nama_bps))]);
            $this->command->getOutput()->writeln('Area <info>'.$provinsi->nama_bps.'</info> Added');
        }

        foreach ($list_kabupaten as $kabupaten) {
            Area::firstOrCreate([
                'group' => 'kabupaten',
                'code' => str_replace('.', '', $kabupaten->kode_bps),
            ])->update(['name' => ucwords(strtolower($kabupaten->nama_bps))]);
            $this->command->getOutput()->writeln('Area <info>'.$kabupaten->nama_bps.'</info> Added');
        }

        foreach ($list_kota as $kota) {
            Area::firstOrCreate([
                'group' => 'kota',
                'code' => str_replace('.', '', $kota->kode_bps),
            ])->update(['name' => ucwords(strtolower($kota->nama_bps))]);
            $this->command->getOutput()->writeln('Area <info>'.$kota->nama_bps.'</info> Added');
        }

        foreach ($list_kecamatan as $kecamatan) {
            Area::firstOrCreate([
                'group' => 'kecamatan',
                'code' => str_replace('.', '', $kecamatan->kode_bps),
            ])->update(['name' => ucwords(strtolower($kecamatan->nama_bps))]);
            $this->command->getOutput()->writeln('Area <info>'.$kecamatan->nama_bps.'</info> Added');
        }

        foreach ($list_kelurahan as $kelurahan) {
            Area::firstOrCreate([
                'group' => 'kelurahan',
                'code' => str_replace('.', '', $kelurahan->kode_bps),
            ])->update(['name' => ucwords(strtolower($kelurahan->nama_bps))]);
            $this->command->getOutput()->writeln('Area <info>'.$kelurahan->nama_bps.'</info> Added');
        }
    }

    public function ask_value()
    {
        $truncate = strtolower($this->command->ask(
            'Truncate first?(Y/n)',
            'Y'
        ));
        if (! in_array($truncate, ['y', 'n'])) {
            $this->command->getOutput()->writeln('<error>Wrong input</error>, pls input it correctly');

            return $this->ask_value();
        }

        return $truncate;
    }
}
