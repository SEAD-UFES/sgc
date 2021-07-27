<?php

namespace Database\Factories;

use App\Models\Gender;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class GenderFactory extends Factory
{
    /**
     * Gender Factory
     *
     * @var string
     */
    protected $model = Gender::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $name = Str::limit($this->faker->word(), 10, $append = '');

        return [
            'name' => $name,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
