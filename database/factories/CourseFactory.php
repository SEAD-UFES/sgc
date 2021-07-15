<?php

namespace Database\Factories;

use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;

class CourseFactory extends Factory
{
    /**
     * Course Factory
     *
     * @var string
     */
    protected $model = Course::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $wordsInTitle = 3;
        $wordsInDescription = 8;

        $name = $this->faker->sentence($wordsInTitle);
        $description = $this->faker->sentence($wordsInDescription);

        $begin = $this->faker
            ->dateTimeBetween($startDate = '+1 year', $endDate = '+2 years', $timezone = 'America/Sao_Paulo');
        $end = $this->faker
            ->dateTimeBetween($startDate = '+3 year', $endDate = '+4 years', $timezone = 'America/Sao_Paulo');

        return [
            'name' => $name,
            'description' => $description,
            'course_type_id' => 1,
            'begin' => $begin,
            'end' => $end,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
