<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UserTypeAssignmentSeeder extends Seeder
{

    protected $tableName = 'user_type_assignments';

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table($this->tableName)->insert([
            'user_id' => 1,
            'user_type_id' => 1,
            'course_id' => null,
            'begin' => Carbon::now(),
            'end' => null,
        ]);

        DB::table($this->tableName)->insert([
            'user_id' => 1,
            'user_type_id' => 4,
            'course_id' => null,
            'begin' => Carbon::now(),
            'end' => Carbon::now()->addYears(1),
        ]);

        DB::table($this->tableName)->insert([
            'user_id' => 7,
            'user_type_id' => 7,
            'course_id' => 5,
            'begin' => Carbon::now(),
            'end' => Carbon::now()->addYears(1),
        ]);
    }
}
