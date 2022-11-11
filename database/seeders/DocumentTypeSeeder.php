<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DocumentTypeSeeder extends Seeder
{
    /**
     * @var string
     */
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
            'name' => 'CPF',
            'description' => 'Cadastro de Pessoas Físicas',
        ]);

        DB::table($this->tableName)->insert([
            'name' => 'CNH',
            'description' => 'Carteira Nacional de Habilitação',
        ]);

        DB::table($this->tableName)->insert([
            'name' => 'Passaporte',
            'description' => 'Passaporte brasileiro emitido pela Polícia Federal ou pelo Ministério das Relações Exteriores',
        ]);

        DB::table($this->tableName)->insert([
            'name' => 'Carteira de Identidade Profissional',
            'description' => 'Carteira de Identidade Profissional emitida por entidades de classe (OAB, CRM, CRBio, CREA)',
        ]);

        DB::table($this->tableName)->insert([
            'name' => 'SIAPE',
            'description' => 'Sistema Integrado de Administração de Pessoal',
        ]);

        DB::table($this->tableName)->insert([
            'name' => 'Ficha de Inscrição - Formulário',
            'description' => '',
        ]);

        DB::table($this->tableName)->insert([
            'name' => 'Termo de cessão de direitos',
            'description' => '',
        ]);

        DB::table($this->tableName)->insert([
            'name' => 'Comprobatório do currículo',
            'description' => '',
        ]);

        DB::table($this->tableName)->insert([
            'name' => 'Diploma de maior titulação',
            'description' => '',
        ]);

        DB::table($this->tableName)->insert([
            'name' => 'Comprovante de residência',
            'description' => '',
        ]);

        DB::table($this->tableName)->insert([
            'name' => 'Comprovante de experiência no magistério no ensino superior',
            'description' => '',
        ]);

        DB::table($this->tableName)->insert([
            'name' => 'Comprovante de quitação eleitoral',
            'description' => '',
        ]);

        DB::table($this->tableName)->insert([
            'name' => 'Certificado de reservista',
            'description' => '',
        ]);

        DB::table($this->tableName)->insert([
            'name' => 'Certidão de casamento',
            'description' => '',
        ]);

        DB::table($this->tableName)->insert([
            'name' => 'Ficha de qualificação',
            'description' => '',
        ]);
        

        DB::table($this->tableName)->insert([
            'name' => 'Experiência - Prática em atividades de processos EAD',
            'description' => '',
        ]);

        DB::table($this->tableName)->insert([
            'name' => 'Experiência - Participação  na  produção  de  materiais didáticos',
            'description' => '',
        ]);

        DB::table($this->tableName)->insert([
            'name' => 'Experiência - Atividades de apoio ao ensino: planejamento pedagógico, coordenação',
            'description' => '',
        ]);

        DB::table($this->tableName)->insert([
            'name' => 'Experiência - Experiência  com  formatação  e  uso  de ambientes virtuais de aprendizagem',
            'description' => '',
        ]);
    }
}
