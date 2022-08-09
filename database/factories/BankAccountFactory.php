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
            'bank_name' => $this->faker->company,
            'agency_number' => (string) $this->faker->randomNumber(4),
            'account_number' => (string) $this->faker->randomNumber(8),
            'employee_id' => Employee::factory(),

            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
