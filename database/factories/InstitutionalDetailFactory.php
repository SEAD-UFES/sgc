<?php

namespace Database\Factories;

use App\Models\InstitutionalDetail;
use Illuminate\Database\Eloquent\Factories\Factory;

class InstitutionalDetailFactory extends Factory
{
    /**
     * Pole Factory
     *
     * @var string
     */
    protected $model = InstitutionalDetail::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $login = $this->faker->unique()->userName;
        return [
            'employee_id' => null,
            'login' => $login,
            'email' => $login . '@' . $this->faker->safeEmailDomain,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
