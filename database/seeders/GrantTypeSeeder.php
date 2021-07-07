<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;

use Illuminate\Database\Seeder;

class GrantTypeSeeder extends Seeder
{
    protected $tableName = 'grant_types';
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table($this->tableName)->insert([
            'name' => 'Mensal',
            'description' => 'Recebimento de cota por mês',
        ]);

        DB::table($this->tableName)->insert([
            'name' => 'Período',
            'description' => 'Recebimento de cota por período',
        ]);
    }
}
