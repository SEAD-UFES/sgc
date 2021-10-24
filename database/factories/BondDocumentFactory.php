<?php

namespace Database\Factories;

use App\Models\BondDocument;
use App\Models\DocumentType;
use App\Models\Bond;
use Illuminate\Database\Eloquent\Factories\Factory;

class BondDocumentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = BondDocument::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'bond_id' => Bond::factory(),
        ];
    }
}
