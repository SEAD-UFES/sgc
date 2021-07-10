<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EmployeeSeeder extends Seeder
{
    protected $tableName = 'employees';

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table($this->tableName)->insert([
            'cpf' => '11111111111',
            'name' => 'Mateus',
            'job' => 'Técnico',
            'gender_id' => 2,
            'birthday' =>  Carbon::create('1980', '09', '12'),
            'birth_state_id' => 1,
            'birth_city' => 'Amaji',
            'id_number' => '20893574028',
            'id_type_id' => 1,
            'id_issue_date' => Carbon::create('2000', '05', '11'),
            'id_issue_agency' => 'SSP',
            'marital_status_id' => 1,
            'spouse_name' => 'Júlia',
            'father_name' => 'João',
            'mother_name' => 'Rosicléia',
            'address_street' => 'Cerejeiras',
            'address_complement' => 'Cond. Jatobá',
            'address_number' => '357',
            'address_district' => 'Morrinhos',
            'address_postal_code' => '35952654',
            'address_state_id' => 3,
            'address_city' => 'Água Branca',
            'area_code' => '046',
            'phone' => '35986521',
            'mobile' => '9598652',
            'email' => 'admin@ufes.br',
        ]);

        DB::table($this->tableName)->insert([
            'cpf' => '22222222222',
            'name' => 'Júlia',
            'job' => 'Técnico',
            'gender_id' => 1,
            'birthday' =>  Carbon::create('1990', '03', '21'),
            'birth_state_id' => 1,
            'birth_city' => 'Amaji',
            'id_number' => '20893574028',
            'id_type_id' => 1,
            'id_issue_date' => Carbon::create('2005', '07', '01'),
            'id_issue_agency' => 'SSP',
            'marital_status_id' => 1,
            'spouse_name' => 'Júlia',
            'father_name' => 'João',
            'mother_name' => 'Rosicléia',
            'address_street' => 'Cerejeiras',
            'address_complement' => 'Cond. Jatobá',
            'address_number' => '357',
            'address_district' => 'Morrinhos',
            'address_postal_code' => '35952654',
            'address_state_id' => 3,
            'address_city' => 'Água Branca',
            'area_code' => '046',
            'phone' => '35986521',
            'mobile' => '9598652',
            'email' => 'diretor@ufes.br',
        ]);
    }
}
