<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    protected $tableName = 'roles';
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table($this->tableName)->insert([
            'name' => 'Admin',
            'description' => 'Super usuário',
            'grant_value' => 0,
            'grant_type_id' => 1,
        ]);

        DB::table($this->tableName)->insert([
            'name' => 'Diretor',
            'description' => 'Diretor da Sead',
            'grant_value' => 10,
            'grant_type_id' => 2,
        ]);

        DB::table($this->tableName)->insert([
            'name' => 'Coordenador de curso',
            'description' => 'Coordenador de curso',
            'grant_value' => 10,
            'grant_type_id' => 1,
        ]);

        DB::table($this->tableName)->insert([
            'name' => 'Assitente UAB',
            'description' => 'Assitente UAB',
            'grant_value' => 10,
            'grant_type_id' => 2,
        ]);

        DB::table($this->tableName)->insert([
            'name' => 'Tutor a Distancia',
            'description' => 'Tutor a Distancia',
            'grant_value' => 10,
            'grant_type_id' => 1,
        ]);

        DB::table($this->tableName)->insert([
            'name' => 'Tutor Presencial',
            'description' => 'Tutor Presencial',
            'grant_value' => 10,
            'grant_type_id' => 2,
        ]);

        DB::table($this->tableName)->insert([
            'name' => 'Professor 1',
            'description' => 'Professor 1',
            'grant_value' => 10,
            'grant_type_id' => 1,
        ]);

        DB::table($this->tableName)->insert([
            'name' => 'Professor 2',
            'description' => 'Professor 2',
            'grant_value' => 10,
            'grant_type_id' => 2,
        ]);

        DB::table($this->tableName)->insert([
            'name' => 'Coordenador de Tutoria',
            'description' => 'Coordenador de Tutoria',
            'grant_value' => 10,
            'grant_type_id' => 1,
        ]);

        DB::table($this->tableName)->insert([
            'name' => 'Professor orientador de TCC',
            'description' => 'Professor orientador de TCC',
            'grant_value' => 10,
            'grant_type_id' => 2,
        ]);

        DB::table($this->tableName)->insert([
            'name' => 'Professor formador de componentes curriculares',
            'description' => 'Professor formador de componentes curriculares',
            'grant_value' => 10,
            'grant_type_id' => 1,
        ]);

        DB::table($this->tableName)->insert([
            'name' => 'Secretário Acadêmico',
            'description' => 'Colaborador da Secretaria Acadêmica da Sead',
            'grant_value' => 10,
            'grant_type_id' => 2,
        ]);

        DB::table($this->tableName)->insert([
            'name' => 'Colaborador LDI',
            'description' => 'Colaborador do LDI da Sead',
            'grant_value' => 10,
            'grant_type_id' => 1,
        ]);
    }
}
