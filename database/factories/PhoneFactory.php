<?php

namespace Database\Factories;

use App\Models\Phone;
use Illuminate\Database\Eloquent\Factories\Factory;

class PhoneFactory extends Factory
{
    /**
     * Pole Factory
     *
     * @var string
     */
    protected $model = Phone::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $type = $this->faker->randomElement(['Fixo', 'Celular']);
        $areaCode = $this->faker->areaCode();
        return [
            'employee_id' => null,
            'area_code' => $areaCode,
            'number' => ($type === 'Fixo' ? ($this->faker->randomElement(['1', '2', '3', '4', '5', '6']) . $this->faker->randomNumber(7)) : ('9' . $this->faker->randomElement(['6', '7', '8', '9']) . $this->faker->randomNumber(7))),
            'type' => $type,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function makeLandline()
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => 'Fixo',
                'number' => $this->faker->randomElement(['1', '2', '3', '4', '5', '6']) . $this->faker->randomNumber(7),
            ];
        });
    }

    public function makeMobile()
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => 'Celular',
                'number' => '9' . $this->faker->randomElement(['6', '7', '8', '9']) . $this->faker->randomNumber(7),
            ];
        });
    }
}
