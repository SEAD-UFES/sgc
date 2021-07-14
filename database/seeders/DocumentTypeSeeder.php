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
            'name' => 'De Colaborador',
            'description' => 'Documentos atuais do Colaborador',
        ]);

        DB::table($this->tableName)->insert([
            'name' => 'De Vínculo',
            'description' => 'Documentos utiilizados para um vínculo específico',
        ]);
    }
}
