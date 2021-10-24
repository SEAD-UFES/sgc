<?php

namespace Database\Factories;

use App\Models\EmployeeDocument;
use App\Models\DocumentType;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmployeeDocumentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = EmployeeDocument::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
          'employee_id' => Employee::factory(),
        ];
    }
}
