<?php

namespace Database\Factories;

use App\Models\CourseType;
use Illuminate\Database\Eloquent\Factories\Factory;

class CourseTypeFactory extends Factory
{
    /**
     * CourseType Factory
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
        return [
            'name' => $this->faker->word(),
            'description' => $this->faker->text($maxChars = 40),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
