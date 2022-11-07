<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Kedeka\InertiaSetting\Model;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::transaction(function () {
            $this->settings();
        });
    }

    public function settings()
    {
        $data = [
            'contact.email' => 'admin@bpcbkalimantan.id',
            'contact.phone' => '089668756162',
            'greeting.description' => 'Deskripsi',
            'greeting.link' => 'https://',
            'greeting.title' => 'Sambutan',
            'site.description' => 'Balai Pelestarian Cagar Budaya Kalimantan Timur Wilayah Kerja : Kaltim, Kalbar, Kalteng, Kalsel, dan Kaltara',
            'site.footer' => '© #year Balai Pelestarian Cagar Budaya Kalimantan. All Rights Reserved.',
            'site.title' => 'BPCB KALTIM',
            'social-media.facebook.label' => 'Bpcb Kalimantan',
            'social-media.facebook.link' => 'https://www.facebook.com/BPCBKALIMANTAN',
            'social-media.instagram.label' => 'bpcbkaltim',
            'social-media.instagram.link' => 'https://instagram.com/bpcbkaltim',
            'social-media.twitter.label' => 'bpcbkaltim',
            'social-media.twitter.link' => 'https://twitter.com/bpcbkaltim',
            'social-media.youtube.label' => 'BPCB Kaltim',
            'social-media.youtube.link' => 'https://www.youtube.com/channel/UCl_pDCpItZ6r2BrHwJl8BsQ',
        ];

        foreach ($data as $key => $value) {
            Model::firstOrCreate([
                'key' => $key,
            ])->update([
                'value' => $value,
            ]);
        }
    }
}
