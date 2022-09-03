<?php

namespace Tests\Feature;

use App\Events\ModelListed;
use App\Models\Document;
use App\Models\EmployeeDocument;
use App\Services\EmployeeDocumentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class EmployeeDocumentServiceTest extends TestCase
{
    use RefreshDatabase;

    private EmployeeDocumentService $service;

    /** @return void  */
    public function __construct()
    {
        parent::__construct();

        $this->service = new EmployeeDocumentService();
    }

    //setting up scenario for all tests
    public function setUp(): void
    {
        parent::setUp();

        Document::factory()->create(
            [
                'original_name' => 'Document Alpha.pdf',
                'documentable_id' => EmployeeDocument::factory()->createOne(),
                'documentable_type' => EmployeeDocument::class,
            ]
        );

        Document::factory()->create(
            [
                'original_name' => 'Document Beta.pdf',
                'documentable_id' => EmployeeDocument::factory()->createOne(),
                'documentable_type' => EmployeeDocument::class,
            ]
        );
    }

    /**
     * @test
     */
    public function documentsShouldBeListed(): void
    {
        Event::fakeFor(function () {
            //execution
            $documents = $this->service->list();

            //verifications
            Event::assertDispatched(ModelListed::class);
            $this->assertCount(2, $documents);
            $this->assertContains('Document Alpha.pdf', $documents->pluck('original_name')->toArray());
            $this->assertContains('Document Beta.pdf', $documents->pluck('original_name')->toArray());
        });
    }

    /**
     * @test
     */
    public function documentShouldBeCreated(): void
    {
        //setting up scenario
        $attributes['file'] = UploadedFile::fake()->create('Document Gama.pdf', 20, 'application/pdf');

        $attributes['document_type_id'] = (string) 1;
        $attributes['employee_id'] = (string) 2;

        Event::fakeFor(function () use ($attributes) {
            //execution
            $this->service->create($attributes);

            //verifications
            Event::assertDispatched('eloquent.created: ' . Document::class);
            $this->assertContains('Document Gama.pdf', Document::whereHasMorph('documentable', EmployeeDocument::class)->pluck('original_name')->toArray());
        });
    }
}
