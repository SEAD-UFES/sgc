<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmployeeSeeder extends Seeder
{
    protected $tableName = 'employees';

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table($this->tableName)->insert([
            'name' => 'Mateus',
            'cpf' => '11111111111',
            'user_id' => 1,
        ]);

        DB::table($this->tableName)->insert([
            'name' => 'Júlia',
            'cpf' => '22222222222',
            'user_id' => 2,
        ]);
    }
}
