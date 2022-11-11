<?php

namespace Database\Seeders;

use App\Enums\Genders;
use App\Enums\MaritalStatuses;
use App\Enums\States;
use App\Models\Address;
use App\Models\Employee;
use App\Models\Identity as Identity;
use App\Models\PersonalDetail;
use App\Models\Phone;
use App\Models\Spouse;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class EmployeeSeeder extends Seeder
{
    /**
     * @var string
     */
    protected $tableName = 'employees';

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $employee1 = Employee::create([
            'cpf' => '11111111111',
            'name' => 'Mateus',
            'gender' => Genders::M->name,
            'email' => 'admin@ufes.br',
        ]);

        PersonalDetail::create([
            'employee_id' => $employee1->id,
            'job' => 'Técnico',
            'birth_date' =>  Carbon::create(1980, 9, 12),
            'birth_state' => States::ES->name,
            'birth_city' => 'Amaji',
            'marital_status' => MaritalStatuses::CASADO->name,
            'father_name' => 'Jonatas',
            'mother_name' => 'Maricléia',
        ]);

        Identity::create([
            'employee_id' => $employee1->id,
            'type_id' => 1,
            'number' => '20893574028',
            'issue_date' => Carbon::create(2000, 5, 11),
            'issuer' => 'SSP',
            'issuer_state' => States::ES->name,
        ]);

        Spouse::create([
            'employee_id' => $employee1->id,
            'name' => 'Júlia',
        ]);

        Address::create([
            'employee_id' => $employee1->id,
            'state' => States::ES->name,
            'street' => 'Cerejeiras',
            'number' => '357',
            'complement' => 'Cond. Jatobá',
            'district' => 'Morrinhos',
            'city' => 'Água Branca',
            'zip_code' => '35952654',
        ]);

        Phone::create([
            'employee_id' => $employee1->id,
            'area_code' => '46',
            'number' => '995598652',
            'type' => 'Celular',
        ]);

        Phone::create([
            'employee_id' => $employee1->id,
            'area_code' => '46',
            'number' => '35986521',
            'type' => 'Fixo',
        ]);


        $employee2 = Employee::create([
            'cpf' => '22222222222',
            'name' => 'Júlia',
            'gender' => Genders::F->name,
            'email' => 'diretor@ufes.br',
        ]);

        PersonalDetail::create([
            'employee_id' => $employee2->id,
            'job' => 'Técnico',
            'birth_date' =>  Carbon::create(1990, 3, 21),
            'birth_state' => States::ES->name,
            'birth_city' => 'Amaji',
            'marital_status' => MaritalStatuses::CASADO->name,
            'father_name' => 'João',
            'mother_name' => 'Rosicléia',
        ]);

        Identity::create([
            'employee_id' => $employee2->id,
            'type_id' => 1,
            'number' => '20893574028',
            'issue_date' => Carbon::create(2005, 7, 1),
            'issuer' => 'SSP',
            'issuer_state' => States::ES->name,
        ]);

        Spouse::create([
            'employee_id' => $employee2->id,
            'name' => 'Mateus',
        ]);

        Address::create([
            'employee_id' => $employee2->id,
            'state' => States::ES->name,
            'street' => 'Cerejeiras',
            'number' => '357',
            'complement' => 'Cond. Jatobá',
            'district' => 'Morrinhos',
            'city' => 'Água Branca',
            'zip_code' => '35952654',
        ]);

        Phone::create([
            'employee_id' => $employee2->id,
            'area_code' => '46',
            'number' => '959866452',
            'type' => 'Celular',
        ]);

        Phone::create([
            'employee_id' => $employee2->id,
            'area_code' => '46',
            'number' => '35986521',
            'type' => 'Fixo',
        ]);
    }
}
