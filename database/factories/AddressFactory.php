<?php

namespace Database\Factories;

use App\Enums\States;
use App\Models\Address;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class AddressFactory extends Factory
{
    /**
     * Pole Factory
     *
     * @var string
     */
    protected $model = Address::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'employee_id' => $this->faker->unique()->numberBetween(1, 100),
            'street' => $this->faker->streetName(),
            'number' => $this->faker->buildingNumber(),
            'complement' => $this->faker->secondaryAddress(),
            'district' => $this->faker->citySuffix(),
            'city' => $this->faker->city(),
            'state' => States::cases()[array_rand(States::cases())],
            'zip_code' => Str::of($this->faker->postcode())->replace('-', ''),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
