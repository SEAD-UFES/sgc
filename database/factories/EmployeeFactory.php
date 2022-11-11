<?php

namespace Database\Factories;

use App\Enums\Genders;
use App\Enums\MaritalStatuses;
use App\Enums\States;
use App\Models\Employee;
use App\Models\DocumentType;
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
        // $areaCode = $this->faker->areaCode();
        $gender = Genders::cases()[array_rand(Genders::cases())];
        $genderParameter = ($gender === Genders::F) ? 'female' : 'male';
        $firstName = $this->faker->firstName(gender: $genderParameter);
        $lastName = $this->faker->lastName();
        $email = Str::of(
            $firstName . '.' .
                $lastName  . '.' .
                $this->faker->unique()->word() . '@' .
                $this->faker->safeEmailDomain()
        )
            ->ascii()
            ->replace(' ', '')
            ->lower();

        return [
            'cpf' => $this->faker->unique()->cpf($formatted = false),
            'name' => $firstName . ' ' . $lastName,
            'gender' => $gender->name,
            'email' => $email,
            'created_at' => now(),
            'updated_at' => now(),


            // 'birth_state_id' => States::cases()[array_rand(States::cases())],
            // 'address_state_id' => States::cases()[array_rand(States::cases())],
            // 'document_type_id' => DocumentType::factory(),
            // 'marital_status' => MaritalStatuses::cases()[array_rand(MaritalStatuses::cases())],

            // 'job' => $this->faker->jobTitle(),
            // 'birth_date' => $this->faker->dateTimeBetween('-50 years', '-19 years'),
            // 'birth_city' => $this->faker->city(),

            // 'id_number' => $this->faker->rg($formatted = false),
            // 'id_issue_date' => $this->faker->dateTimeBetween('-5 years', 'now'),
            // 'id_issue_agency' => 'SSP/' . $this->faker->stateAbbr(),

            // 'spouse_name' => $this->faker->name(),
            // 'father_name' => $this->faker->name($gender = 'male'),
            // 'mother_name' => $this->faker->name($gender = 'female'),

            // 'address_street' => $this->faker->streetName(),
            // 'address_complement' => $this->faker->realText($maxChars = 30),
            // 'address_number' => $this->faker->buildingNumber(),
            // 'address_district' => $this->faker->city(),
            // 'address_zip_code' => Str::of($this->faker->postcode())->replace('-', ''),
            // 'address_city' => $this->faker->city(),

            // 'area_code' => $areaCode,
            // 'phone' => $areaCode . $this->faker->landline($formatted = false),
            // 'mobile' => $areaCode . $this->faker->cellphone($formatted = false),

        ];
    }

    /**
     * Indicates the created Employee must use data already in the Database.
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    // public function assumePopulatedDatabase()
    // {
    //     return $this->state(function (array $attributes) {

    //         $gender = Genders::cases()[array_rand(Genders::cases())];
    //         $genderName = ['F' => 'female', 'M' => 'male'];
    //         // $spouseGender = $gender == 'Feminino' ? 'Masculino' : 'Feminino';
    //         $firstName = $this->faker->firstName($genderName[$gender->name]);
    //         $lastName = $this->faker->lastName();
    //         // $maritalStatus = MaritalStatuses::cases()[array_rand(MaritalStatuses::cases())];

    //         $email = Str::of(
    //             $firstName . '.' .
    //                 $lastName  . '.' .
    //                 $this->faker->unique()->word() . '@' .
    //                 $this->faker->safeEmailDomain()
    //         )
    //             ->ascii()
    //             ->replace(' ', '')
    //             ->lower();

    //         // $spouseName =  in_array($maritalStatus, ['Casado(a)', 'Separado(a)', 'União Estável'])
    //         //     ? $this->faker->firstName($genderName[$spouseGender]) . ' ' . $lastName
    //         //     : null;

    //         return [
    //             'gender' => $gender,
    //             // 'birth_state_id' => States::cases()[array_rand(States::cases())],
    //             // 'address_state_id' => States::cases()[array_rand(States::cases())],
    //             // 'document_type_id' => random_int(1, 3),
    //             // 'marital_status' => $maritalStatus,

    //             'name' => $firstName . ' ' . $lastName,

    //             // 'spouse_name' => $spouseName,
    //             // 'father_name' => $this->faker->firstName($gender = 'male') . ' ' . $lastName,
    //             // 'mother_name' => $this->faker->firstName($gender = 'female') . ' ' . $lastName,

    //             'email' => $email
    //         ];
    //     });
    // }
}
