<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BondSeeder extends Seeder
{
    protected $tableName = 'bonds';
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table($this->tableName)->insert([
            'course_id' => 1,
            'employee_id' => 1,
            'role_id' => 1,
            'pole_id' => 1,
            //'classroom_id' => null,
            'begin' => Carbon::create('2020', '03', '05'),
            'end' => Carbon::create('2027', '07', '03'),
            'terminated_on' => null,
            'volunteer' => false,
            'impediment' => false,
            'uaba_checked_on' => Carbon::create('2021', '07', '01'),
        ]);

        DB::table($this->tableName)->insert([
            'course_id' => 2,
            'employee_id' => 1,
            'role_id' => 2,
            'pole_id' => 1,
            //'classroom_id' => null,
            'begin' => Carbon::create('2020', '03', '05'),
            'end' => Carbon::create('2027', '07', '03'),
            'terminated_on' => Carbon::create('2020', '09', '05'),
            'volunteer' => false,
            'impediment' => false,
            'uaba_checked_on' => Carbon::create('2021', '07', '01'),
        ]);

        DB::table($this->tableName)->insert([
            'course_id' => 3,
            'employee_id' => 1,
            'role_id' => 3,
            'pole_id' => 1,
            //'classroom_id' => null,
            'begin' => Carbon::create('2020', '03', '05'),
            'end' => Carbon::create('2020', '09', '05'),
            'terminated_on' => null,
            'volunteer' => false,
            'impediment' => false,
            'uaba_checked_on' => Carbon::create('2021', '07', '01'),
        ]);

        DB::table($this->tableName)->insert([
            'course_id' => 4,
            'employee_id' => 1,
            'role_id' => 4,
            'pole_id' => 2,
            //'classroom_id' => null,
            'begin' => Carbon::create('2020', '03', '05'),
            'end' => Carbon::create('2029', '09', '05'),
            'terminated_on' => null,
            'volunteer' => false,
            'impediment' => true,
            'uaba_checked_on' => Carbon::create('2021', '07', '01'),
        ]);


        DB::table($this->tableName)->insert([
            'course_id' => 5,
            'employee_id' => 1,
            'role_id' => 3,
            'pole_id' => 1,
            //'classroom_id' => null,
            'begin' => Carbon::create('2020', '03', '05'),
            'end' => Carbon::create('2029', '09', '05'),
            'terminated_on' => null,
            'volunteer' => false,
            'impediment' => false,
            'uaba_checked_on' => Carbon::create('2021', '07', '01'),
        ]);

        DB::table($this->tableName)->insert([
            'course_id' => 6,
            'employee_id' => 2,
            'role_id' => 4,
            'pole_id' => 5,
            //'classroom_id' => null,
            'begin' => Carbon::create('2020', '03', '05'),
            'end' => Carbon::create('2027', '07', '03'),
            'terminated_on' => null,
            'volunteer' => true,
            'impediment' => false,
            'uaba_checked_on' => Carbon::create('2021', '07', '01'),
        ]);
    }
}
