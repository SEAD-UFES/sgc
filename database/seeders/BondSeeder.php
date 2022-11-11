<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BondSeeder extends Seeder
{
    /**
     * @var string
     */
    protected $tableName = 'bonds';
   
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table($this->tableName)->insert([
            'employee_id' => 1, //
            'role_id' => 1, //
            'begin' => Carbon::create(2020, 3, 5), //
            'hiring_process' => '01/2022', //
            'terminated_at' => null, //
            'volunteer' => false, //
            'created_at' => Carbon::create(2020, 3, 5),
            'updated_at' => Carbon::create(2020, 3, 5),

            // 'course_id' => 1,
            // 'pole_id' => 1,
            // 'impediment' => false,
            // 'impediment_description' => '',
            // 'uaba_checked_at' => Carbon::create('2021', '07', '01'),
        ]);

        DB::table($this->tableName)->insert([
            'employee_id' => 1, //
            'role_id' => 2, //
            'begin' => Carbon::create(2020, 3, 5), //
            'hiring_process' => '01/2022', //
            'terminated_at' => Carbon::create(2020, 9, 5), //
            'volunteer' => false, //
            'created_at' => Carbon::create(2020, 3, 5),
            'updated_at' => Carbon::create(2020, 3, 5),

            // 'course_id' => 2,
            // 'pole_id' => 1,
            // 'impediment' => false,
            // 'impediment_description' => '',
            // 'uaba_checked_at' => Carbon::create('2021', '07', '01'),
        ]);

        DB::table($this->tableName)->insert([
            'employee_id' => 1, //
            'role_id' => 3, //
            'begin' => Carbon::create(2020, 3, 5), //
            'hiring_process' => '01/2022', //
            'terminated_at' => null, //
            'volunteer' => false, //
            'created_at' => Carbon::create(2020, 3, 5),
            'updated_at' => Carbon::create(2020, 3, 5),

            // 'course_id' => 3,
            // 'pole_id' => 1,
            // 'impediment' => false,
            // 'impediment_description' => '',
            // 'uaba_checked_at' => Carbon::create('2021', '07', '01'),
        ]);

        DB::table($this->tableName)->insert([
            'employee_id' => 1, //
            'role_id' => 4, //
            'begin' => Carbon::create(2020, 3, 5), //
            'hiring_process' => '01/2022', //
            'terminated_at' => null, //
            'volunteer' => false, //
            'created_at' => Carbon::create(2020, 3, 5),
            'updated_at' => Carbon::create(2020, 3, 5),

            // 'course_id' => 4,
            // 'pole_id' => 2,
            // 'impediment' => true,
            // 'impediment_description' => 'Assinatura não confere com o documento',
            // 'uaba_checked_at' => Carbon::create('2021', '07', '01'),
        ]);


        DB::table($this->tableName)->insert([
            'employee_id' => 1, //
            'role_id' => 3, //
            'begin' => Carbon::create(2020, 3, 5), //
            'hiring_process' => '01/2022', //
            'terminated_at' => null, //
            'volunteer' => false, //
            'created_at' => Carbon::create(2020, 3, 5),
            'updated_at' => Carbon::create(2020, 3, 5),

            // 'course_id' => 5,
            // 'pole_id' => 1,
            // 'impediment' => false,
            // 'impediment_description' => '',
            // 'uaba_checked_at' => Carbon::create('2021', '07', '01'),
        ]);

        DB::table($this->tableName)->insert([
            'employee_id' => 2, //
            'role_id' => 4, //
            'begin' => Carbon::create(2020, 3, 5), //
            'hiring_process' => '01/2022', //
            'terminated_at' => null, //
            'volunteer' => true, //
            'created_at' => Carbon::create(2020, 3, 5),
            'updated_at' => Carbon::create(2020, 3, 5),

            // 'course_id' => 6,
            // 'pole_id' => 5,
            // 'impediment' => false,
            // 'impediment_description' => '',
            // 'uaba_checked_at' => Carbon::create('2021', '07', '01'),
        ]);
    }
}
