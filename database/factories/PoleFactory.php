<?php

namespace Database\Factories;

use App\Models\Pole;
use Illuminate\Database\Eloquent\Factories\Factory;

class PoleFactory extends Factory
{
    /**
     * Pole Factory
     *
     * @var string
     */
    protected $model = Pole::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word(),
            'description' => $this->faker->text($maxCharacters = 40),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
