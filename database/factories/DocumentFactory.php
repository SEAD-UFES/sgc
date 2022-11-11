<?php

namespace Database\Factories;

use App\Models\Bond;
use App\Models\Document;
use App\Models\DocumentType;
use Illuminate\Database\Eloquent\Factories\Factory;

class DocumentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Document::class;

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
            'file_name' => $this->faker->lexify('????????????????????.pdf'),
            'document_type_id' => DocumentType::factory(),
            'related_id' => Bond::factory(),
            'related_type' => Bond::class,
            'file_data' => $fileBase64,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
