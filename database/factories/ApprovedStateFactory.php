<?php

namespace Database\Factories;

use App\Models\ApprovedState;
use Illuminate\Database\Eloquent\Factories\Factory;

class ApprovedStateFactory extends Factory
{
    /**
     * ApprovedState Factory
     *
     * @var string
     */
    protected $model = ApprovedState::class;

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
