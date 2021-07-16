<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DocumentTypeSeeder extends Seeder
{
        protected $tableName = 'document_types';

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table($this->tableName)->insert([
            'name' => 'RG',
            'description' => 'Carteira de Identidade',
        ]);

        DB::table($this->tableName)->insert([
            'name' => 'CNH',
            'description' => 'Carteira Nacional de Habilitação',
        ]);

        DB::table($this->tableName)->insert([
            'name' => 'SIAPE',
            'description' => 'Sistema Integrado de Administração de Pessoal',
        ]);
    }
}
