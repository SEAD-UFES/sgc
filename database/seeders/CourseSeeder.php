<?php

namespace Database\Seeders;

use App\Enums\Degrees;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CourseSeeder extends Seeder
{
    /**
     * @var string
     */
    protected $tableName = 'courses';

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Licenciatura
        DB::table($this->tableName)->insert(['name' => 'Artes Visuais', 'description' => 'Curso de Artes Visuais', 'degree' => Degrees::L->name, 'lms_url' => 'https://ead.sead.ufes.br/course/1']);
        DB::table($this->tableName)->insert(['name' => 'Ciências Biológicas', 'description' => 'Curso de Ciências Biológicas', 'degree' => Degrees::L->name, 'lms_url' => 'https://ead.sead.ufes.br/course/2']);
        DB::table($this->tableName)->insert(['name' => 'Educação Física', 'description' => 'Curso de Educação Física', 'degree' => Degrees::L->name, 'lms_url' => 'https://ead.sead.ufes.br/course/3']);
        DB::table($this->tableName)->insert(['name' => 'Filosofia', 'description' => 'Curso de Filosofia', 'degree' => Degrees::L->name, 'lms_url' => 'https://ead.sead.ufes.br/course/4']);
        DB::table($this->tableName)->insert(['name' => 'Física', 'description' => 'Curso de Física', 'degree' => Degrees::L->name, 'lms_url' => 'https://ead.sead.ufes.br/course/5']);
        DB::table($this->tableName)->insert(['name' => 'História', 'description' => 'Curso de História', 'degree' => Degrees::L->name, 'lms_url' => 'https://ead.sead.ufes.br/course/6']);
        DB::table($this->tableName)->insert(['name' => 'Letras Italiano', 'description' => 'Curso de Letras Italiano', 'degree' => Degrees::L->name, 'lms_url' => 'https://ead.sead.ufes.br/course/7']);
        DB::table($this->tableName)->insert(['name' => 'Pedagogia', 'description' => 'Curso de Pedagogia', 'degree' => Degrees::L->name, 'lms_url' => 'https://ead.sead.ufes.br/course/8']);
        DB::table($this->tableName)->insert(['name' => 'Química', 'description' => 'Curso de Química', 'degree' => Degrees::L->name, 'lms_url' => 'https://ead.sead.ufes.br/course/9']);

        //Bacharelado
        DB::table($this->tableName)->insert(['name' => 'Administração', 'description' => 'Curso de Administração', 'degree' => Degrees::B->name, 'lms_url' => 'https://ead.sead.ufes.br/course/10']);
        DB::table($this->tableName)->insert(['name' => 'Biblioteconomia', 'description' => 'Curso de Biblioteconomia', 'degree' => Degrees::B->name, 'lms_url' => 'https://ead.sead.ufes.br/course/11']);
        DB::table($this->tableName)->insert(['name' => 'Ciências Contábeis', 'description' => 'Curso de Ciências Contábeis', 'degree' => Degrees::B->name, 'lms_url' => 'https://ead.sead.ufes.br/course/12']);

        // Especialização
        DB::table($this->tableName)->insert(['name' => 'Ciência é 10!', 'description' => 'Curso Ciência é 10!', 'degree' => Degrees::E->name, 'lms_url' => 'https://ead.sead.ufes.br/course/13']);
        DB::table($this->tableName)->insert(['name' => 'Educação em Direitos Humanos', 'description' => 'Curso de Educação em Direitos Humanos', 'degree' => Degrees::E->name, 'lms_url' => 'https://ead.sead.ufes.br/course/14']);
        DB::table($this->tableName)->insert(['name' => 'Ensino de Matemática para o Ensino Médio', 'description' => 'Curso de Ensino de Matemática para o Ensino Médio', 'degree' => Degrees::E->name, 'lms_url' => 'https://ead.sead.ufes.br/course/15']);
        DB::table($this->tableName)->insert(['name' => 'Epidemiologia', 'description' => 'Curso de Epidemiologia', 'degree' => Degrees::E->name, 'lms_url' => 'https://ead.sead.ufes.br/course/16']);
        DB::table($this->tableName)->insert(['name' => 'Filosofia e Psicanálise', 'description' => 'Curso de Filosofia e Psicanálise', 'degree' => Degrees::E->name, 'lms_url' => 'https://ead.sead.ufes.br/course/17']);
        DB::table($this->tableName)->insert(['name' => 'Gênero e Diversidade na Escola', 'description' => 'Curso de Gênero e Diversidade na Escola', 'degree' => Degrees::E->name, 'lms_url' => 'https://ead.sead.ufes.br/course/18']);
        DB::table($this->tableName)->insert(['name' => 'Gestão de Agronegócios', 'description' => 'Curso de Gestão de Agronegócios', 'degree' => Degrees::E->name, 'lms_url' => 'https://ead.sead.ufes.br/course/19']);
        DB::table($this->tableName)->insert(['name' => 'Gestão em Saúde', 'description' => 'Curso de Gestão em Saúde', 'degree' => Degrees::E->name, 'lms_url' => 'https://ead.sead.ufes.br/course/20']);
        DB::table($this->tableName)->insert(['name' => 'Gestão Pública', 'description' => 'Curso de Gestão Pública', 'degree' => Degrees::E->name, 'lms_url' => 'https://ead.sead.ufes.br/course/21']);
        DB::table($this->tableName)->insert(['name' => 'Logística', 'description' => 'Curso de Logística', 'degree' => Degrees::E->name, 'lms_url' => 'https://ead.sead.ufes.br/course/22']);
        DB::table($this->tableName)->insert(['name' => 'Oratória, Transversalidade e Didática da Fala', 'description' => 'Curso de Oratória, Transversalidade e Didática da Fala', 'degree' => Degrees::E->name, 'lms_url' => 'https://ead.sead.ufes.br/course/23']);

        // Aperfeiçoamento
        DB::table($this->tableName)->insert(['name' => 'Educação Ambiental', 'description' => 'Curso de Educação Ambiental', 'degree' => Degrees::A->name, 'lms_url' => 'https://ead.sead.ufes.br/course/24']);
        DB::table($this->tableName)->insert(['name' => 'Educação do Campo', 'description' => 'Curso de Educação do Campo', 'degree' => Degrees::A->name, 'lms_url' => 'https://ead.sead.ufes.br/course/25']);
        DB::table($this->tableName)->insert(['name' => 'Educação para a Diversidade e Cidadania', 'description' => 'Curso de Educação para a Diversidade e Cidadania', 'degree' => Degrees::A->name, 'lms_url' => 'https://ead.sead.ufes.br/course/26']);
        DB::table($this->tableName)->insert(['name' => 'Educação para as Relações Etnicorraciais', 'description' => 'Curso de Educação para as Relações Etnicorraciais', 'degree' => Degrees::A->name, 'lms_url' => 'https://ead.sead.ufes.br/course/27']);
        DB::table($this->tableName)->insert(['name' => 'Gestão de Políticas Públicas em Gênero e Raça', 'description' => 'Curso de Gestão de Políticas Públicas em Gênero e Raça', 'degree' => Degrees::A->name, 'lms_url' => 'https://ead.sead.ufes.br/course/28']);
    }
}
