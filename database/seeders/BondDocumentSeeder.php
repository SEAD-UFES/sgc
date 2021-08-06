<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BondDocumentSeeder extends Seeder
{
    protected $tableName = 'bond_documents';
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table($this->tableName)->insert([
            'original_name' => 'dummy_bond.png',
            'document_type_id' => 1,
            'bond_id' => 1,
            'file_data' => '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',
        ]);

        DB::table($this->tableName)->insert([
            'original_name' => 'doc01.png',
            'document_type_id' => 2,
            'bond_id' => 1,
            'file_data' => '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',
        ]);

        DB::table($this->tableName)->insert([
            'original_name' => 'doc02.png',
            'document_type_id' => 3,
            'bond_id' => 6,
            'file_data' => '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',
        ]);

        DB::table($this->tableName)->insert([
            'original_name' => 'doc03.png',
            'document_type_id' => 4,
            'bond_id' => 6,
            'file_data' => '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',
        ]);
    }
}
