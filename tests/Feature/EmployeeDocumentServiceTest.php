<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Document;
use Mockery\MockInterface;
use App\Models\EmployeeDocument;
use App\Services\DocumentService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EmployeeDocumentServiceTest extends TestCase
{
    use RefreshDatabase;

    //setting up scenario for all tests
    public function setUp(): void
    {
        parent::setUp();

        Document::factory()->create(
            [
                'original_name' => 'Document Alpha.pdf',
                'documentable_id' => EmployeeDocument::factory(),
                'documentable_type' => EmployeeDocument::class,
            ]
        );

        Document::factory()->create(
            [
                'original_name' => 'Document Beta.pdf',
                'documentable_id' => EmployeeDocument::factory(),
                'documentable_type' => EmployeeDocument::class,
            ]
        );

        $this->service = new DocumentService;
    }

    /**
     * @test
     */
    public function documentsShouldBeListed()
    {
        $this->service->documentClass = EmployeeDocument::class;
        $docList = $this->service->list();

        //verifications
        $this->assertEquals('Document Alpha.pdf', $docList->first()->original_name);
        $this->assertEquals(2, $docList->count());
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

        Storage::fake('local');
        $attributes['file'] = UploadedFile::fake()->create('Document Gama.pdf', 20, 'application/pdf');

        $attributes['document_type_id'] = 1;
        $attributes['employee_id'] = 1;

        //execution
        $service->documentClass = EmployeeDocument::class;
        $service->create($attributes);

        //verifications
        $this->assertEquals('Document Gama.pdf', Document::whereHasMorph('documentable', EmployeeDocument::class)->skip(2)->first()->original_name);
        $this->assertCount(3, Document::whereHasMorph('documentable', EmployeeDocument::class)->get());
        $this->assertCount(3, EmployeeDocument::all());
    }
}
