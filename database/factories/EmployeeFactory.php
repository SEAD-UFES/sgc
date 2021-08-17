<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\Gender;
use App\Models\State;
use App\Models\DocumentType;
use App\Models\MaritalStatus;
use App\Helpers\TextHelper;
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
            'address_district' => $this->faker->city(),
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

    /**
     * Indicates the created Employee must use data already in the Database.
     * @return \Illumintate\Database\Eloquent\Factories\Factory
     */
    public function assumePopulatedDatabase()
    {
        return $this->state(function (array $attributes) {

            $genderId = Gender::all()->random()->id;
            $genderName = [1 => 'female', 2 => 'male'];
            $spouseGenderId = $genderId == 1 ? 2 : 1;
            $firstName = $this->faker->firstName($genderName[$genderId]);
            $lastName = $this->faker->lastName();
            $maritalStatusId = MaritalStatus::all()->random()->id;

            $email = Str::of(
                $firstName . '.' .
                    $lastName  . '.' .
                    $this->faker->unique()->word() . '@' .
                    $this->faker->safeEmailDomain()
            )
                ->ascii()
                ->replace(' ', '')
                ->lower();

            $spouseName =  in_array($maritalStatusId, [2, 3, 6])
                ? $this->faker->firstName($genderName[$spouseGenderId]) . ' ' . $lastName
                : null;

            return [
                'gender_id' => $genderId,
                'birth_state_id' => State::all()->random(),
                'address_state_id' => State::all()->random(),
                'document_type_id' => random_int(1, 3),
                'marital_status_id' => $maritalStatusId,

                'name' => $firstName . ' ' . $lastName,

                'spouse_name' => $spouseName,
                'father_name' => $this->faker->firstName($gender = 'male') . ' ' . $lastName,
                'mother_name' => $this->faker->firstName($gender = 'female') . ' ' . $lastName,

                'email' => $email
            ];
        });
    }
}
