<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class ProdAdminSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'login' => 'admin@ufes.br',
            'password' => Hash::make('changeme'),
            'active' => true,
            'employee_id' => null,
        ]);
        /** @var User $sysAdm */
        $sysAdm = User::where('login', 'admin@ufes.br')->first();
        $sysAdmId = $sysAdm->getAttribute('id');
        
        DB::table('responsibilities')->insert([
            'user_id' => $sysAdmId,
            'user_type_id' => 1,
            'course_id' => null,
            'begin' => Carbon::now(),
            'end' => null,
        ]);
    }
}
