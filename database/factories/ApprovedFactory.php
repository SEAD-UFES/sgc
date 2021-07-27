<?php

namespace Database\Factories;

use App\Models\Approved;
use App\Models\Course;
use App\Models\Role;
use App\Models\Pole;
use App\Models\ApprovedState;
use Illuminate\Database\Eloquent\Factories\Factory;

class ApprovedFactory extends Factory
{
    /**
     * Approved Factory
     *
     * @var string
     */
    protected $model = Approved::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // annoucements are like 01/2020, 99/2021, etc.
        $announcement = $this->faker->numerify('##') . '/' . now()->year;

        return [
            'name' => $this->faker->word(),
            'email' => $this->faker->email(),
            'area_code' => $this->faker->areaCode(),
            'phone' => $this->faker->landline($formatted = true),
            'mobile' => $this->faker->cellphone($formatted = true),
            'announcement' => $announcement,
            'course_id' => Course::factory(),
            'role_id' => Role::factory(),
            'pole_id' => Pole::factory(),
            'approved_state_id' => ApprovedState::factory(),

            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
