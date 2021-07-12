<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ApprovedStateSeeder extends Seeder
{
    protected $tableName = 'approved_states';
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table($this->tableName)->insert([
            'name' => 'Não contatado',
            'description' => 'Aprovado ainda não contatado',
        ]);

        DB::table($this->tableName)->insert([
            'name' => 'Contatado',
            'description' => 'Aprovado contatado, com resposta ainda pendente',
        ]);

        DB::table($this->tableName)->insert([
            'name' => 'Aceitante',
            'description' => 'Aprovado contatado que aceitou o cargo',
        ]);

        DB::table($this->tableName)->insert([
            'name' => 'Desistente',
            'description' => 'Aprovado contatado que desistiu do cargo',
        ]);
    }
}
