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
        ]);

        DB::table($this->tableName)->insert([
            'name' => 'Administrador',
            'description' => 'Administrador do SGC',
        ]);

        DB::table($this->tableName)->insert([
            'name' => 'Coordenador de curso',
            'description' => 'Coordenador de curso',
        ]);

        DB::table($this->tableName)->insert([
            'name' => 'Assitente UAB',
            'description' => 'Assitente UAB',
        ]);

        DB::table($this->tableName)->insert([
            'name' => 'Tutor a Distancia',
            'description' => 'Tutor a Distancia',
        ]);

        DB::table($this->tableName)->insert([
            'name' => 'Tutor Presencial',
            'description' => 'Tutor Presencial',
        ]);

        DB::table($this->tableName)->insert([
            'name' => 'Professor 1',
            'description' => 'Professor 1',
        ]);

        DB::table($this->tableName)->insert([
            'name' => 'Professor 2',
            'description' => 'Professor 2',
        ]);

        DB::table($this->tableName)->insert([
            'name' => 'Coordenador de Tutoria',
            'description' => 'Coordenador de Tutoria',
        ]);

        DB::table($this->tableName)->insert([
            'name' => 'Professor orientador de TCC',
            'description' => 'Professor orientador de TCC',
        ]);

        DB::table($this->tableName)->insert([
            'name' => 'Professor formador de componentes curriculares',
            'description' => 'Professor formador de componentes curriculares',
        ]);
    }
}
