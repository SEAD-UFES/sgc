<?php

namespace Database\Factories;

use App\Enums\Genders;
use App\Models\Employee;
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
        $areaCode = $this->faker->areaCode();
        
        return [

            'gender' => strval($this->faker->randomElement(Genders::getValuesInAlphabeticalOrder())),
            'birth_state_id' => State::factory(),
            'address_state_id' => State::factory(),
            'document_type_id' => DocumentType::factory(),
            'marital_status_id' => MaritalStatus::factory(),

            'cpf' => $this->faker->unique()->cpf($formatted = false),
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

            'area_code' => $areaCode,
            'phone' => $areaCode . $this->faker->landline($formatted = false),
            'mobile' => $areaCode . $this->faker->cellphone($formatted = false),
            'email' => $this->faker->unique()->email(),

            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    /**
     * Indicates the created Employee must use data already in the Database.
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function assumePopulatedDatabase()
    {
        return $this->state(function (array $attributes) {

            $gender = strval($this->faker->randomElement(Genders::getValuesInAlphabeticalOrder()));
            $genderName = ['Feminino' => 'female', 'Masculino' => 'male'];
            $spouseGender = $gender == 'Feminino' ? 'Masculino' : 'Feminino';
            $firstName = $this->faker->firstName($genderName[$gender]);
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
                ? $this->faker->firstName($genderName[$spouseGender]) . ' ' . $lastName
                : null;

            return [
                'gender' => $gender,
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
