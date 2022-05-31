<?php

namespace Database\Factories;

use App\Models\Role;
use App\Models\GrantType;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoleFactory extends Factory
{
    /**
     * Role Factory
     *
     * @var string
     */
    protected $model = Role::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $wordsInDescription = 5;
        $description = $this->faker->sentence($wordsInDescription);

        return [
          'name' => $this->faker->word(),
          'description' => $description,
          'grant_value' => $this->faker->numberBetween(50000, 350000),
          'grant_type_id' => GrantType::factory(),
          'created_at' => now(),
          'updated_at' => now(),
        ];
    }
}
