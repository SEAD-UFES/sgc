<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GenderSeeder extends Seeder
{
    protected $tableName = 'genders';
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table($this->tableName)->insert([
            'name' => 'Feminino',
        ]);

        DB::table($this->tableName)->insert([
            'name' => 'Masculino',
        ]);
    }
}
