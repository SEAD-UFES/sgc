<?php

namespace Database\Factories;

use App\Models\UserType;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserTypeFactory extends Factory
{
    /**
     * UserType Factory
     *
     * @var string
     */
    protected $model = UserType::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word(),
            'acronym' => $this->faker->lexify('???'),
            'description' => $this->faker->text($maxChars = 40),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }


    /**
     * Return an Administrator User Type (acronym `adm`)
     * @return \Illumintate\Database\Eloquent\Factories\Factory
     */
    public function admin()
    {
        return $this->state(function (array $attributes) {
            return [
                'acronym' => 'adm'
            ];
        });
    }
}
