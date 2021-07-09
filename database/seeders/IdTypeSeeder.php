<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IdTypeSeeder extends Seeder
{
    protected $tableName = 'id_types';
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table($this->tableName)->insert([
            'name' => 'RG',
        ]);

        DB::table($this->tableName)->insert([
            'name' => 'Habilitação',
        ]);

        DB::table($this->tableName)->insert([
            'name' => 'SIAPE',
        ]);
    }
}
