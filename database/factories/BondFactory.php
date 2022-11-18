<?php

namespace Database\Factories;

use App\Models\Bond;
use App\Models\Employee;
use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

class BondFactory extends Factory
{
    /**
     * Bond Factory
     *
     * @var string
     */
    protected $model = Bond::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // $issueStatus = random_int(1, 3); // 1 - Not reviewed; 2 - Reviewed-Applicant; 3 - Reviewed-Not applicant

        return [
            'employee_id' => Employee::factory()->createOne()->getAttribute('id'),
            'role_id' => Role::factory()->createOne()->getAttribute('id'),
            // 'volunteer' => $this->faker->boolean($changeOfGettingTrue = 50),
            'volunteer' => false,
            'hiring_process' => '123/2023',

            'begin' => $this->faker->dateTimeBetween('-2 years', '-1 year'),
            // 'terminated_at' => $this->faker->dateTimeBetween('+1 year', '+2 years'),
            'terminated_at' => null,
            // 'impediment' => ($issueStatus == 3) ? true : false,
            // 'impediment_description' =>  ($issueStatus == 3) ? 'Problema na documentação' : '',
            // 'uaba_checked_at' => ($issueStatus != 1) ? $this->faker->dateTimeBetween('-2 years', '-1 year') : null,

            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
