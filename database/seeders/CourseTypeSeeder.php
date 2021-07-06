<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CourseTypeSeeder extends Seeder
{
    protected $tableName = 'course_types';
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table($this->tableName)->insert([
            'name' => 'Aperfeiçoamento',
            'description' => 'Curso de Aperfeiçoamento',
        ]);

        DB::table($this->tableName)->insert([
            'name' => 'Bacharelado',
            'description' => 'Curso de Bacharelado',
        ]);

        DB::table($this->tableName)->insert([
            'name' => 'Especialização',
            'description' => 'Curso de Especialização',
        ]);

        DB::table($this->tableName)->insert([
            'name' => 'Extensão',
            'description' => 'Curso de Extensão',
        ]);

        DB::table($this->tableName)->insert([
            'name' => 'Licenciatura',
            'description' => 'Curso de Licenciatura',
        ]);
    }
}
