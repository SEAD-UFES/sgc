<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BondPoleSeeder extends Seeder
{
    /**
     * @var string
     */
    protected $tableName = 'bond_pole';
   
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table($this->tableName)->insert([
            'bond_id' => 1,
            'pole_id' => 1,
            'created_at' => Carbon::create(2020, 3, 5),
            'updated_at' => Carbon::create(2020, 3, 5),
        ]);

        DB::table($this->tableName)->insert([
            'bond_id' => 2,
            'pole_id' => 1,
            'created_at' => Carbon::create(2020, 3, 5),
            'updated_at' => Carbon::create(2020, 3, 5),
        ]);

        DB::table($this->tableName)->insert([
            'bond_id' => 3,
            'pole_id' => 1,
            'created_at' => Carbon::create(2020, 3, 5),
            'updated_at' => Carbon::create(2020, 3, 5),
        ]);

        DB::table($this->tableName)->insert([
            'bond_id' => 4,
            'pole_id' => 2,
            'created_at' => Carbon::create(2020, 3, 5),
            'updated_at' => Carbon::create(2020, 3, 5),
        ]);


        DB::table($this->tableName)->insert([
            'bond_id' => 5,
            'pole_id' => 1,
            'created_at' => Carbon::create(2020, 3, 5),
            'updated_at' => Carbon::create(2020, 3, 5),
        ]);

        DB::table($this->tableName)->insert([
            'bond_id' => 6,
            'pole_id' => 5,
            'created_at' => Carbon::create(2020, 3, 5),
            'updated_at' => Carbon::create(2020, 3, 5),
        ]);
    }
}
