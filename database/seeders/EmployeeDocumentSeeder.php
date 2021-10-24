<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmployeeDocumentSeeder extends Seeder
{
    protected $tableName = 'employee_documents';
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table($this->tableName)->insert([
            'employee_id' => 1,
        ]);

        DB::table($this->tableName)->insert([
            'employee_id' => 2,
        ]);

        DB::table($this->tableName)->insert([
            'employee_id' => 1,
        ]);

        DB::table($this->tableName)->insert([
            'employee_id' => 2,
        ]);
    }
}
