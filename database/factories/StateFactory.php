<?php

namespace Database\Factories;

use App\Models\State;
use Illuminate\Database\Eloquent\Factories\Factory;

class StateFactory extends Factory
{
    /**
     * State Factory
     *
     * @var string
     */
    protected $model = State::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'uf' => $this->faker->lexify('??'),
            'name' => $this->faker->word(),
            'ibge_uf_code' => $this->faker->numberBetween(0, 99),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
