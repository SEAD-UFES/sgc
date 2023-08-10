<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\UserType;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * User Factory
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // 'name' =>  fake()->name(),
            'login' => fake()->unique()->safeEmail(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'active' => true,
            'email_verified_at' => now(),
            'employee_id' => Employee::factory(),
            'remember_token' => Str::random(10),
            //'user_type_id' => UserType::factory(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return static
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Indicate that the model's employee_id should be null.
     *
     * @return static
     */
    public function withoutEmployee()
    {
        return $this->state(function (array $attributes) {
            return [
                'employee_id' => null,
            ];
        });
    }
}
