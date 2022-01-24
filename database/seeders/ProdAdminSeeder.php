<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class ProdAdminSeeder extends Seeder
{

    public function run()
    {
        DB::table('users')->insert([
            'email' => 'admin@sead.ufes.br',
            'password' => Hash::make('changeme'),
            'active' => true,
            'employee_id' => null,
        ]);
        
        DB::table('user_type_assignments')->insert([
            'user_id' => 1,
            'user_type_id' => 1,
            'course_id' => null,
            'begin' => Carbon::now(),
            'end' => null,
        ]);
    }
}
