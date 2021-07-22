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
        $fileBase64 = "JVBERi0xLjIgCjkgMCBvYmoKPDwKPj4Kc3RyZWFtCkJULyA5IFRmKF"
            . "Rlc3QpJyBFVAplbmRzdHJlYW0KZW5kb2JqCjQgMCBvYmoKPDwKL1R5cGUgL1Bh"
            . "Z2UKL1BhcmVudCA1IDAgUgovQ29udGVudHMgOSAwIFIKPj4KZW5kb2JqCjUgMC"
            . "BvYmoKPDwKL0tpZHMgWzQgMCBSIF0KL0NvdW50IDEKL1R5cGUgL1BhZ2VzCi9N"
            . "ZWRpYUJveCBbIDAgMCA5OSA5IF0KPj4KZW5kb2JqCjMgMCBvYmoKPDwKL1BhZ2"
            . "VzIDUgMCBSCi9UeXBlIC9DYXRhbG9nCj4+CmVuZG9iagp0cmFpbGVyCjw8Ci9S"
            . "b290IDMgMCBSCj4+CiUlRU9G";

        return [
            'document_type_id' => DocumentType::factory(),
            'bond_id' => Bond::factory(),
            'original_name' => $this->faker->lexify('????????????????????.pdf'),
            'file_data' => $fileBase64,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
