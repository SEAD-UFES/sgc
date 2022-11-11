<?php

namespace Database\Factories;

use App\Enums\States;
use App\Models\DocumentType;
use App\Models\Identity;
use Illuminate\Database\Eloquent\Factories\Factory;

class IdentityFactory extends Factory
{
    /**
     * Pole Factory
     *
     * @var string
     */
    protected $model = Identity::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'employee_id' => null,
            'type_id' => DocumentType::factory(),
            'number' => $this->faker->unique()->numerify('#########'),
            'issue_date' => $this->faker->date(),
            'issuer' =>  mb_substr($this->faker->company, 0, 5),
            'issuer_state' => States::cases()[array_rand(States::cases())],
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
