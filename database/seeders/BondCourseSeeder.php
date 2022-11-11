<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BondCourseSeeder extends Seeder
{
    /**
     * @var string
     */
    protected $tableName = 'bond_course';
   
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table($this->tableName)->insert([
            'bond_id' => 1,
            'course_id' => 1,
            'created_at' => Carbon::create(2020, 3, 5),
            'updated_at' => Carbon::create(2020, 3, 5),
        ]);

        DB::table($this->tableName)->insert([
            'bond_id' => 2,
            'course_id' => 2,
            'created_at' => Carbon::create(2020, 3, 5),
            'updated_at' => Carbon::create(2020, 3, 5),
        ]);

        DB::table($this->tableName)->insert([
            'bond_id' => 3,
            'course_id' => 3,
            'created_at' => Carbon::create(2020, 3, 5),
            'updated_at' => Carbon::create(2020, 3, 5),
        ]);

        DB::table($this->tableName)->insert([
            'bond_id' => 4,
            'course_id' => 4,
            'created_at' => Carbon::create(2020, 3, 5),
            'updated_at' => Carbon::create(2020, 3, 5),
        ]);


        DB::table($this->tableName)->insert([
            'bond_id' => 5,
            'course_id' => 5,
            'created_at' => Carbon::create(2020, 3, 5),
            'updated_at' => Carbon::create(2020, 3, 5),
        ]);

        DB::table($this->tableName)->insert([
            'bond_id' => 6,
            'course_id' => 6,
            'created_at' => Carbon::create(2020, 3, 5),
            'updated_at' => Carbon::create(2020, 3, 5),
        ]);
    }
}
