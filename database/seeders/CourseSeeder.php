<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CourseSeeder extends Seeder
{
    protected $tableName = 'courses';

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Licenciatura (5)
        DB::table($this->tableName)->insert(['name' => 'Artes Visuais','description' => 'Curso de Artes Visuais','course_type_id' => 5,'begin' => Carbon::create('2020', '01', '01'),'end' => Carbon::create('2027', '07', '01'),]);
        DB::table($this->tableName)->insert(['name' => 'Ciências Biológicas','description' => 'Curso de Ciências Biológicas','course_type_id' => 5,'begin' => Carbon::create('2020', '01', '01'),'end' => Carbon::create('2027', '07', '01'),]);
        DB::table($this->tableName)->insert(['name' => 'Educação Física','description' => 'Curso de Educação Física','course_type_id' => 5,'begin' => Carbon::create('2020', '01', '01'),'end' => Carbon::create('2027', '07', '01'),]);
        DB::table($this->tableName)->insert(['name' => 'Filosofia','description' => 'Curso de Filosofia','course_type_id' => 5,'begin' => Carbon::create('2020', '01', '01'),'end' => Carbon::create('2027', '07', '01'),]);
        DB::table($this->tableName)->insert(['name' => 'Física','description' => 'Curso de Física','course_type_id' => 5,'begin' => Carbon::create('2020', '01', '01'),'end' => Carbon::create('2027', '07', '01'),]);
        DB::table($this->tableName)->insert(['name' => 'História','description' => 'Curso de História','course_type_id' => 5,'begin' => Carbon::create('2020', '01', '01'),'end' => Carbon::create('2027', '07', '01'),]);
        DB::table($this->tableName)->insert(['name' => 'Letras Italiano','description' => 'Curso de Letras Italiano','course_type_id' => 5,'begin' => Carbon::create('2020', '01', '01'),'end' => Carbon::create('2027', '07', '01'),]);
        DB::table($this->tableName)->insert(['name' => 'Pedagogia','description' => 'Curso de Pedagogia','course_type_id' => 5,'begin' => Carbon::create('2020', '01', '01'),'end' => Carbon::create('2027', '07', '01'),]);
        DB::table($this->tableName)->insert(['name' => 'Química','description' => 'Curso de Química','course_type_id' => 5,'begin' => Carbon::create('2020', '01', '01'),'end' => Carbon::create('2027', '07', '01'),]);

        //Bacharelado (2)
        DB::table($this->tableName)->insert(['name' => 'Administração','description' => 'Curso de Administração','course_type_id' => 2,'begin' => Carbon::create('2020', '01', '01'),'end' => Carbon::create('2027', '07', '01'),]);
        DB::table($this->tableName)->insert(['name' => 'Biblioteconomia','description' => 'Curso de Biblioteconomia','course_type_id' => 2,'begin' => Carbon::create('2020', '01', '01'),'end' => Carbon::create('2027', '07', '01'),]);
        DB::table($this->tableName)->insert(['name' => 'Ciências Contábeis','description' => 'Curso de Ciências Contábeis','course_type_id' => 2,'begin' => Carbon::create('2020', '01', '01'),'end' => Carbon::create('2027', '07', '01'),]);

        // Especialização (3)
        DB::table($this->tableName)->insert(['name' => 'Ciência é 10!','description' => 'Curso Ciência é 10!','course_type_id' => 3,'begin' => Carbon::create('2020', '01', '01'),'end' => Carbon::create('2027', '07', '01'),]);
        DB::table($this->tableName)->insert(['name' => 'Educação em Direitos Humanos','description' => 'Curso de Educação em Direitos Humanos','course_type_id' => 3,'begin' => Carbon::create('2020', '01', '01'),'end' => Carbon::create('2027', '07', '01'),]);
        DB::table($this->tableName)->insert(['name' => 'Ensino de Matemática para o Ensino Médio','description' => 'Curso de Ensino de Matemática para o Ensino Médio','course_type_id' => 3,'begin' => Carbon::create('2020', '01', '01'),'end' => Carbon::create('2027', '07', '01'),]);
        DB::table($this->tableName)->insert(['name' => 'Epidemiologia','description' => 'Curso de Epidemiologia','course_type_id' => 3,'begin' => Carbon::create('2020', '01', '01'),'end' => Carbon::create('2027', '07', '01'),]);
        DB::table($this->tableName)->insert(['name' => 'Filosofia e Psicanálise','description' => 'Curso de Filosofia e Psicanálise','course_type_id' => 3,'begin' => Carbon::create('2020', '01', '01'),'end' => Carbon::create('2027', '07', '01'),]);
        DB::table($this->tableName)->insert(['name' => 'Gênero e Diversidade na Escola','description' => 'Curso de Gênero e Diversidade na Escola','course_type_id' => 3,'begin' => Carbon::create('2020', '01', '01'),'end' => Carbon::create('2027', '07', '01'),]);
        DB::table($this->tableName)->insert(['name' => 'Gestão de Agronegócios','description' => 'Curso de Gestão de Agronegócios','course_type_id' => 3,'begin' => Carbon::create('2020', '01', '01'),'end' => Carbon::create('2027', '07', '01'),]);
        DB::table($this->tableName)->insert(['name' => 'Gestão em Saúde','description' => 'Curso de Gestão em Saúde','course_type_id' => 3,'begin' => Carbon::create('2020', '01', '01'),'end' => Carbon::create('2027', '07', '01'),]);
        DB::table($this->tableName)->insert(['name' => 'Gestão Pública','description' => 'Curso de Gestão Pública','course_type_id' => 3,'begin' => Carbon::create('2020', '01', '01'),'end' => Carbon::create('2027', '07', '01'),]);
        DB::table($this->tableName)->insert(['name' => 'Logística','description' => 'Curso de Logística','course_type_id' => 3,'begin' => Carbon::create('2020', '01', '01'),'end' => Carbon::create('2027', '07', '01'),]);
        DB::table($this->tableName)->insert(['name' => 'Oratória, Transversalidade e Didática da Fala','description' => 'Curso de Oratória, Transversalidade e Didática da Fala','course_type_id' => 3,'begin' => Carbon::create('2020', '01', '01'),'end' => Carbon::create('2027', '07', '01'),]);

        // Aperfeiçoamento (1)
        DB::table($this->tableName)->insert(['name' => 'Educação Ambiental','description' => 'Curso de Educação Ambiental','course_type_id' => 1,'begin' => Carbon::create('2020', '01', '01'),'end' => Carbon::create('2027', '07', '01'),]);
        DB::table($this->tableName)->insert(['name' => 'Educação do Campo','description' => 'Curso de Educação do Campo','course_type_id' => 1,'begin' => Carbon::create('2020', '01', '01'),'end' => Carbon::create('2027', '07', '01'),]);
        DB::table($this->tableName)->insert(['name' => 'Educação para a Diversidade e Cidadania','description' => 'Curso de Educação para a Diversidade e Cidadania','course_type_id' => 1,'begin' => Carbon::create('2020', '01', '01'),'end' => Carbon::create('2027', '07', '01'),]);
        DB::table($this->tableName)->insert(['name' => 'Educação para as Relações Etnicorraciais','description' => 'Curso de Educação para as Relações Etnicorraciais','course_type_id' => 1,'begin' => Carbon::create('2020', '01', '01'),'end' => Carbon::create('2027', '07', '01'),]);
        DB::table($this->tableName)->insert(['name' => 'Gestão de Políticas Públicas em Gênero e Raça','description' => 'Curso de Gestão de Políticas Públicas em Gênero e Raça','course_type_id' => 1,'begin' => Carbon::create('2020', '01', '01'),'end' => Carbon::create('2027', '07', '01'),]);
    }
}
