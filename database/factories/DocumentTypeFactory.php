<?php

namespace Database\Factories;

use App\Models\DocumentType;
use Illuminate\Database\Eloquent\Factories\Factory;

class DocumentTypeFactory extends Factory
{
    /**
     * DocumentType Factory
     *
     * @var string
     */
    protected $model = DocumentType::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word(),
            'description' => $this->faker->text($maxNbChars = 40),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
