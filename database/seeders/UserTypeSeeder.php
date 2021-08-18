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
            'name' => 'Administrador',
            'acronym' => 'adm',
            'description' => 'Administrador do sistema',
        ]);

        DB::table($this->tableName)->insert([
            'name' => 'Diretor',
            'acronym' => 'dir',
            'description' => 'Diretor da Sead',
        ]);

        DB::table($this->tableName)->insert([
            'name' => 'Assitente UAB',
            'acronym' => 'ass',
            'description' => 'Assitente UAB',
        ]);

        DB::table($this->tableName)->insert([
            'name' => 'Secretário Acadêmico',
            'acronym' => 'sec',
            'description' => 'Colaborador da Secretaria Acadêmica da Sead',
        ]);

        DB::table($this->tableName)->insert([
            'name' => 'Colaborador LDI',
            'acronym' => 'ldi',
            'description' => 'Colaborador do LDI da Sead',
        ]);

        DB::table($this->tableName)->insert([
            'name' => 'Colaborador Bolsista',
            'acronym' => 'gra',
            'description' => 'Colaborador que recebe bolsa',
        ]);

        DB::table($this->tableName)->insert([
            'name' => 'Coordenador de Curso',
            'acronym' => 'coord',
            'description' => 'Cordenador de um curso',
        ]);
    }
}
