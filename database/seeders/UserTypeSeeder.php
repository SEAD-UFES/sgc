<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserTypeSeeder extends Seeder
{
    protected $tableName = 'user_types';
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table($this->tableName)->insert([
            'name' => 'Admin',
            'description' => 'Administrador do sistema',
        ]);

        DB::table($this->tableName)->insert([
            'name' => 'Diretor',
            'description' => 'Diretor da Sead',
        ]);

        DB::table($this->tableName)->insert([
            'name' => 'Assitente UAB',
            'description' => 'Assitente UAB',
        ]);

        DB::table($this->tableName)->insert([
            'name' => 'Secretário Acadêmico',
            'description' => 'Colaborador da Secretaria Acadêmica da Sead',
        ]);

        DB::table($this->tableName)->insert([
            'name' => 'Colaborador LDI',
            'description' => 'Colaborador do LDI da Sead',
        ]);

        DB::table($this->tableName)->insert([
            'name' => 'Colaborador Bolsista',
            'description' => 'Colaborador que recebe bolsa',
        ]);
    }
}
