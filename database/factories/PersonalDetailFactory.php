<?php

namespace Database\Factories;

use App\Enums\MaritalStatuses;
use App\Enums\States;
use App\Models\PersonalDetail;
use Illuminate\Database\Eloquent\Factories\Factory;

class PersonalDetailFactory extends Factory
{
    /**
     * Pole Factory
     *
     * @var string
     */
    protected $model = PersonalDetail::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'employee_id' => null,
            'job' => $this->faker->word(),
            'birth_date' => $this->faker->date($format = 'Y-m-d', $max = 'now'),
            'birth_state' => States::cases()[array_rand(States::cases())],
            'birth_city' => $this->faker->city(),
            'marital_status' => MaritalStatuses::cases()[array_rand(MaritalStatuses::cases())],
            'father_name' => $this->faker->name(gender: 'male'),
            'mother_name' => $this->faker->name(gender: 'female'),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
