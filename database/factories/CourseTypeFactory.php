<?php

namespace Database\Factories;

use App\Models\CourseType;
use Illuminate\Database\Eloquent\Factories\Factory;

class CourseTypeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CourseType::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $wordsInName = 2;
        $wordsInDescription = 5;

        return [
            'name' => $this->faker->sentence($wordsInName),
            'description' => $this->faker->sentence($wordsInDescription),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
