<?php

namespace Database\Factories;

use App\Enums\CallStates;
use App\Models\Applicant;
use App\Models\Course;
use App\Models\Role;
use App\Models\Pole;
use Illuminate\Database\Eloquent\Factories\Factory;

class ApplicantFactory extends Factory
{
    /**
     * Applicant Factory
     *
     * @var string
     */
    protected $model = Applicant::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $areaCode = $this->faker->areaCode();
        // annoucements are like 01/2020, 99/2021, etc.
        $hiring_process = $this->faker->numerify('##') . '/' . now()->year;

        return [
            'name' => $this->faker->word(),
            'email' => $this->faker->email(),
            'area_code' => $areaCode,
            'landline' => $areaCode . $this->faker->landline($formatted = false),
            'mobile' => $areaCode . $this->faker->cellphone($formatted = false),
            'hiring_process' => $hiring_process,
            'course_id' => Course::factory(),
            'role_id' => Role::factory(),
            'pole_id' => Pole::factory(),
            'call_state' => CallStates::cases()[array_rand(CallStates::cases())],

            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
