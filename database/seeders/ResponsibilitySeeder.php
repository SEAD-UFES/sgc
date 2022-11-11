<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ResponsibilitySeeder extends Seeder
{
    /**
     * @var string
     */
    protected $tableName = 'responsibilities';

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sysAdmId = DB::table('users')->select('id')->where('login', 'admin@ufes.br')->limit(1)->value('id');
        $dirId = DB::table('users')->select('id')->where('login', 'diretor@ufes.br')->limit(1)->value('id');

        DB::table($this->tableName)->insert([
            'user_id' => $sysAdmId,
            'user_type_id' => 1,
            'course_id' => null,
            'begin' => Carbon::now(),
            'end' => null,
        ]);

        DB::table($this->tableName)->insert([
            'user_id' => $sysAdmId,
            'user_type_id' => 4,
            'course_id' => null,
            'begin' => Carbon::now(),
            'end' => Carbon::now()->addYears(1),
        ]);

        DB::table($this->tableName)->insert([
            'user_id' => $dirId,
            'user_type_id' => 6,
            'course_id' => 5,
            'begin' => Carbon::now(),
            'end' => Carbon::now()->addYears(1),
        ]);
    }
}
