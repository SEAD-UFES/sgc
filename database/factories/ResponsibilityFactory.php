<?php

namespace Database\Factories;

use App\Models\Responsibility;
use App\Models\User;
use App\Models\UserType;
use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;

class ResponsibilityFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Responsibility::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'user_type_id' => UserType::factory(),
            'course_id' => Course::factory(),
            'begin' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'end' => $this->faker->dateTimeBetween('1 year', '2 years'),
        ];
    }
}
