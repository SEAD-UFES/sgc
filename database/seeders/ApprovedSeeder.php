<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ApprovedSeeder extends Seeder
{
    protected $tableName = 'approveds';
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table($this->tableName)->insert([
            'name' => 'Rowan Atkinson',
            'email' => 'rowan@bean.com',
            'area_code' => '27',
            'phone' => '33259856',
            'mobile' => '992366541',
            'announcement' => '77/2021',
            'course_id' => '1',
            'pole_id' => '1',
            'role_id' => '1',
            'approved_state_id' => 1,
        ]);

        DB::table($this->tableName)->insert([
            'name' => 'Jim Carrey',
            'email' => 'the@mask.com',
            'area_code' => '27',
            'phone' => '33259856',
            'mobile' => '992366541',
            'announcement' => '77/2021',
            'course_id' => '2',
            'pole_id' => '2',
            'role_id' => '2',
            'approved_state_id' => 1,
        ]);

        DB::table($this->tableName)->insert([
            'name' => 'Eddie Murphy',
            'email' => 'prince@newyork.com',
            'area_code' => '27',
            'phone' => '33259856',
            'mobile' => '992366541',
            'announcement' => '77/2021',
            'course_id' => '3',
            'pole_id' => '3',
            'role_id' => '3',
            'approved_state_id' => 1,
        ]);
    }
}
