<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MaritalStatusSeeder extends Seeder
{
    protected $tableName = 'marital_statuses';
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table($this->tableName)->insert([
            'name' => 'Solteiro (a)',
        ]);

        DB::table($this->tableName)->insert([
            'name' => 'Casado (a)',
        ]);

        DB::table($this->tableName)->insert([
            'name' => 'Separado (a)',
        ]);
        DB::table($this->tableName)->insert([
            'name' => 'Divorciado (a)',
        ]);

        DB::table($this->tableName)->insert([
            'name' => 'Viúvo (a)',
        ]);

        DB::table($this->tableName)->insert([
            'name' => 'União Estável',
        ]);
    }
}
