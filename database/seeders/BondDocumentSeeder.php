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
            'bond_id' => 1,
        ]);

        DB::table($this->tableName)->insert([
            'bond_id' => 1,
        ]);

        DB::table($this->tableName)->insert([
            'bond_id' => 6,
        ]);

        DB::table($this->tableName)->insert([
            'bond_id' => 6,
        ]);
    }
}
