<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        \App\Models\User::truncate();
        \App\Models\Team::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $data = [
            [
                'name' => 'Rilo',
                'email' => 'rilo.arbabillah@gmail.com',
            ],
            [
                'name' => 'Rizky Hajar',
                'email' => 'riskihajar@gmail.com',
            ],
        ];

        foreach ($data as $item) {
            $user = \App\Models\User::create([
                ...$item,
                'email_verified_at' => now(),
                'password' => Hash::make('qweasdzxc'),
            ]);

            $user->switchTeam(\App\Models\Team::forceCreate([
                'user_id' => $user->id,
                'name' => explode(' ', $user->name, 2)[0]."'s Team",
                'personal_team' => true,
            ]));
        }
    }
}
