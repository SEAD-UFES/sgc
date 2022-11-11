<?php

namespace Database\Factories;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BankAccount>
 */
class BankAccountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'employee_id' => null,
            'bank_name' => $this->faker->company,
            'agency_number' => (string) $this->faker->randomNumber(4),
            'account' => (string) $this->faker->randomNumber(8),

            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
