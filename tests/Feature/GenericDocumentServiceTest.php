<?php

namespace Tests\Feature;

use App\Events\ModelRead;
use App\Models\BondDocument;
use App\Models\Document;
use App\Models\Employee;
use App\Models\EmployeeDocument;
use App\Services\DocumentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class GenericDocumentServiceTest extends TestCase
{
    use RefreshDatabase;

    private DocumentService $service;

    public function __construct()
    {
        parent::__construct();

        $this->service = new DocumentService(EmployeeDocument::class, Employee::class);
    }

    //setting up scenario for all tests
    public function setUp(): void
    {
        parent::setUp();

        Document::factory()->create(
            [
                'original_name' => 'Document Employee Alpha.pdf',
                'documentable_id' => EmployeeDocument::factory()->create()->id,
                'documentable_type' => EmployeeDocument::class,
            ]
        );

        Document::factory()->create(
            [
                'original_name' => 'Document Bond Beta.pdf',
                'documentable_id' => BondDocument::factory()->create()->id,
                'documentable_type' => BondDocument::class,
            ]
        );

        //
    }

    /**
     * @test
     */
    public function documentShouldBeRetrieved()
    {
        //setting up scenario
        $document = Document::find(1);

        Event::fakeFor(function () use ($document) {
            //execution
            $document = $this->service->read($document);

            //verifications
            Event::assertDispatched(ModelRead::class);
            $this->assertEquals('Document Employee Alpha.pdf', $document->original_name);
            $this->assertCount(2, Document::all());
        });
    }

    /**
     * @test
     */
    public function documentShouldBePreparedToDownload()
    {
        Event::fakeFor(function () {
            //execution
            $document = $this->service->getDocument(1);

            //verifications
            Event::assertDispatched(ModelRead::class);
            $this->assertEquals('Document Employee Alpha.pdf', $document->get('name'));
            $this->assertEquals(EmployeeDocument::class, $document->get('class'));
        });
    }
}
