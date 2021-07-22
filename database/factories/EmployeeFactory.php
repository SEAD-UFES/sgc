<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\Gender;
use App\Models\State;
use App\Models\DocumentType;
use App\Models\MaritalStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmployeeFactory extends Factory
{
    /**
     * Employee Factory
     *
     * @var string
     */
    protected $model = Employee::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [

            'gender_id' => Gender::factory(),
            'birth_state_id' => State::factory(),
            'address_state_id' => State::factory(),
            'document_type_id' => DocumentType::factory(),
            'marital_status_id' => MaritalStatus::factory(),

            'cpf' => $this->faker->numerify('###########'),
            'name' => $this->faker->name(),
            'job' => $this->faker->jobTitle(),
            'birthday' => $this->faker->dateTimeBetween('-50 years', '-19 years'),
            'birth_city' => $this->faker->city(),

            'id_number' => $this->faker->numerify('##########'),
            'id_issue_date' => $this->faker->dateTimeBetween('-5 years', 'now'),
            'id_issue_agency' => $this->faker->text($maxChars = 10),

            'spouse_name' => $this->faker->name(),
            'father_name' => $this->faker->name(),
            'mother_name' => $this->faker->name(),

            'address_street' => $this->faker->streetAddress(),
            'address_complement' => $this->faker->text($maxChars = 30),
            'address_number' => $this->faker->numerify('###'),
            'address_postal_code' => $this->faker->numerify('#######'),
            'address_city' => $this->faker->city(),

            'area_code' => $this->faker->numerify('0##'),
            'phone' => $this->faker->numerify('##### ####'),
            'mobile' => $this->faker->numerify('##### ####'),
            'email' => $this->faker->email(),

            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
