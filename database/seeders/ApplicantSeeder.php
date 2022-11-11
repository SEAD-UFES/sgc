<?php

namespace Database\Seeders;

use App\Enums\CallStates;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ApplicantSeeder extends Seeder
{
    /**
     * @var string
     */
    protected $tableName = 'applicants';

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
            'landline' => '2733259856',
            'mobile' => '27992366541',
            'hiring_process' => '77/2021',
            'course_id' => '1',
            'pole_id' => '1',
            'role_id' => '1',
            'call_state' => CallStates::NC->name,
        ]);

        DB::table($this->tableName)->insert([
            'name' => 'Jim Carrey',
            'email' => 'the@mask.com',
            'area_code' => '27',
            'landline' => '2733259856',
            'mobile' => '27992366541',
            'hiring_process' => '77/2021',
            'course_id' => '2',
            'pole_id' => '2',
            'role_id' => '2',
            'call_state' => CallStates::NC->name,
        ]);

        DB::table($this->tableName)->insert([
            'name' => 'Eddie Murphy',
            'email' => 'prince@newyork.com',
            'area_code' => '27',
            'landline' => '2733259856',
            'mobile' => '27992366541',
            'hiring_process' => '77/2021',
            'course_id' => '3',
            'pole_id' => '3',
            'role_id' => '3',
            'call_state' => CallStates::NC->name,
        ]);
    }
}
