<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::factory(5)->create()->each(function ($user) {
            $user->assignRole('writer');
        });

        \App\Models\User::factory(5)->create()->each(function ($user) {
            $user->assignRole('writer');
            $user->assignRole('editor');
        });
    }
}
