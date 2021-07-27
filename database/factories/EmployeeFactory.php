<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\Gender;
use App\Models\State;
use App\Models\DocumentType;
use App\Models\MaritalStatus;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

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

            'cpf' => $this->faker->cpf($formatted = false),
            'name' => $this->faker->name(),
            'job' => $this->faker->jobTitle(),
            'birthday' => $this->faker->dateTimeBetween('-50 years', '-19 years'),
            'birth_city' => $this->faker->city(),

            'id_number' => $this->faker->rg($formatted = false),
            'id_issue_date' => $this->faker->dateTimeBetween('-5 years', 'now'),
            'id_issue_agency' => 'SSP/' . $this->faker->stateAbbr(),

            'spouse_name' => $this->faker->name(),
            'father_name' => $this->faker->name($gender = 'male'),
            'mother_name' => $this->faker->name($gender = 'female'),

            'address_street' => $this->faker->streetName(),
            'address_complement' => $this->faker->realText($maxChars = 30),
            'address_number' => $this->faker->buildingNumber(),
            'address_postal_code' => Str::of($this->faker->postcode())->replace('-', ''),
            'address_city' => $this->faker->city(),

            'area_code' => $this->faker->areaCode(),
            'phone' => $this->faker->landline($formatted = true),
            'mobile' => $this->faker->cellphone($formatted = true),
            'email' => $this->faker->email(),

            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
