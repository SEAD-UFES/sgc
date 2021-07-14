<?php

namespace Database\Factories;

use App\Models\IdType;
use Illuminate\Database\Eloquent\Factories\Factory;

class IdTypeFactory extends Factory
{
    /**
     * IdType Factory
     *
     * @var string
     */
    protected $model = IdType::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
