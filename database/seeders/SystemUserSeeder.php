<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SystemUserSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'id' => 1,
            'login' => 'sgc_system',
            'password' => Hash::make('&b6x8WJBN7Np'),
            'active' => true,
            'employee_id' => null,
        ]);
    }
}
