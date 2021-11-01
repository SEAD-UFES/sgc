<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Bond;
use App\Models\Document;
use Mockery\MockInterface;
use App\Models\BondDocument;
use App\Models\DocumentType;
use App\Services\DocumentService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BondDocumentServiceTest extends TestCase
{
    use RefreshDatabase;

    //setting up scenario for all tests
    public function setUp(): void
    {
        parent::setUp();

        Document::factory()->create(
            [
                'original_name' => 'Document Alpha.pdf',
                'documentable_id' => BondDocument::factory()->create([
                    'bond_id' => Bond::factory()->create()->id,
                ])->id,
                'documentable_type' => BondDocument::class,
                'document_type_id' => DocumentType::factory()->create(
                    [
                        'name' => 'Type One',
                    ]
                )->id,
            ]
        );

        Document::factory()->create(
            [
                'original_name' => 'Document Beta.pdf',
                'documentable_id' => BondDocument::factory()->create([
                    'bond_id' => Bond::factory()->create()->id,
                ])->id,
                'documentable_type' => BondDocument::class,
                'document_type_id' => DocumentType::factory()->create(
                    [
                        'name' => 'Type Two',
                    ]
                )->id,
            ]
        );

        $this->service = new DocumentService;
        $this->service->documentClass = BondDocument::class;
    }

    /**
     * @test
     */
    public function documentsShouldBeListed()
    {
        Event::fakeFor(function () {
            //execution
            $documents = $this->service->list();

            //verifications
            Event::assertDispatched('eloquent.listed: ' . Document::class);
            $this->assertCount(2, $documents);
            $this->assertEquals('Document Alpha.pdf', $documents[0]->original_name);
            $this->assertEquals('Document Beta.pdf', $documents[1]->original_name);
        });
    }

    /**
     * @test
     */
    public function rightsShouldBeListed()
    {
        Document::factory()->create(
            [
                'original_name' => 'Document Rights.pdf',
                'documentable_id' => BondDocument::factory()->create([
                    'bond_id' => Bond::factory()->create(
                        [
                            'uaba_checked_at' => now(),
                            'impediment' => false,
                        ]
                    )->id,
                ])->id,
                'documentable_type' => BondDocument::class,
                'document_type_id' => DocumentType::factory()->create(
                    [
                        'name' => 'Ficha de Inscrição - Termos e Licença',
                    ]
                )->id,
            ]
        );

        Event::fakeFor(function () {
            //execution
            $documents = $this->service->listRights();

            //verifications
            Event::assertDispatched('eloquent.listed: ' . Document::class);
            $this->assertCount(1, $documents);
            $this->assertEquals('Document Rights.pdf', $documents->first()->original_name);
        });
    }

    /**
     * @test
     */
    public function documentShouldBeCreated()
    {
        //setting up scenario

        //overwriting 'getFileData' method and asserting parameter
        $service = $this->partialMock(DocumentService::class, function (MockInterface $service) {
            $fileBase64 = "JVBERi0xLjIgCjkgMCBvYmoKPDwKPj4Kc3RyZWFtCkJULyA5IFRmKF"
                . "Rlc3QpJyBFVAplbmRzdHJlYW0KZW5kb2JqCjQgMCBvYmoKPDwKL1R5cGUgL1Bh"
                . "Z2UKL1BhcmVudCA1IDAgUgovQ29udGVudHMgOSAwIFIKPj4KZW5kb2JqCjUgMC"
                . "BvYmoKPDwKL0tpZHMgWzQgMCBSIF0KL0NvdW50IDEKL1R5cGUgL1BhZ2VzCi9N"
                . "ZWRpYUJveCBbIDAgMCA5OSA5IF0KPj4KZW5kb2JqCjMgMCBvYmoKPDwKL1BhZ2"
                . "VzIDUgMCBSCi9UeXBlIC9DYXRhbG9nCj4+CmVuZG9iagp0cmFpbGVyCjw8Ci9S"
                . "b290IDMgMCBSCj4+CiUlRU9G";

            $service->shouldReceive('getFileData')->once()->andReturn($fileBase64);
        });
        $service->documentClass = BondDocument::class;

        Storage::fake('local');
        $attributes['file'] = UploadedFile::fake()->create('Document Gama.pdf', 20, 'application/pdf');

        $attributes['document_type_id'] = 1;
        $attributes['bond_id'] = 1;

        Event::fakeFor(function () use ($service, $attributes) {
            //execution
            $service->create($attributes);

            //verifications
            Event::assertDispatched('eloquent.created: ' . Document::class);
            $this->assertEquals('Document Gama.pdf', Document::whereHasMorph('documentable', BondDocument::class)->skip(2)->first()->original_name);
            $this->assertCount(3, Document::whereHasMorph('documentable', BondDocument::class)->get());
            $this->assertCount(3, BondDocument::all());
        });
    }
}
