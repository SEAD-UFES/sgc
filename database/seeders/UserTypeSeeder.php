<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserTypeSeeder extends Seeder
{
    /**
     * @var string
     */
    protected $tableName = 'user_types';

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table($this->tableName)->insert([
            'id' => 1,
            'name' => 'Administrador',
            'acronym' => 'adm',
            'description' => 'Administrador do sistema',
        ]);

        DB::table($this->tableName)->insert([
            'id' => 2,
            'name' => 'Diretor',
            'acronym' => 'dir',
            'description' => 'Diretor da Sead',
        ]);

        DB::table($this->tableName)->insert([
            'id' => 3,
            'name' => 'Assitente UAB',
            'acronym' => 'ass',
            'description' => 'Assitente UAB',
        ]);

        DB::table($this->tableName)->insert([
            'id' => 4,
            'name' => 'Secretário Acadêmico',
            'acronym' => 'sec',
            'description' => 'Colaborador da Secretaria Acadêmica da Sead',
        ]);

        DB::table($this->tableName)->insert([
            'id' => 5,
            'name' => 'Colaborador LDI',
            'acronym' => 'ldi',
            'description' => 'Colaborador do LDI da Sead',
        ]);

        DB::table($this->tableName)->insert([
            'id' => 6,
            'name' => 'Coordenador',
            'acronym' => 'coord',
            'description' => 'Coordenador que recebe bolsa',
        ]);
    }
}
