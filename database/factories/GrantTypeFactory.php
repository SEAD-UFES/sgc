<?php

namespace Database\Factories;

use App\Models\GrantType;
use Illuminate\Database\Eloquent\Factories\Factory;

class GrantTypeFactory extends Factory
{
    /**
     * GrantType Factory
     *
     * @var string
     */
    protected $model = GrantType::class;

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
