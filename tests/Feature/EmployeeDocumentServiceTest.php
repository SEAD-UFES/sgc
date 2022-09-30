<?php

namespace Tests\Feature;

use App\Events\ModelListed;
use App\Models\Document;
use App\Models\EmployeeDocument;
use App\Repositories\EmployeeDocumentRepository;
use App\Services\Dto\StoreDocumentDto;
use App\Services\EmployeeDocumentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
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

        $this->service = new EmployeeDocumentService(new EmployeeDocumentRepository());
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
        $attributes['fileName'] = 'Document Gama.pdf';
        $attributes['fileData'] = (string) 'data:application/pdf;base64,';
        $attributes['documentTypeId'] = (string) 1;
        $attributes['referentId'] = (string) 2;

        $dto = new StoreDocumentDto($attributes);

        Event::fakeFor(function () use ($dto) {
            //execution
            $this->service->create($dto);

            //verifications
            Event::assertDispatched('eloquent.created: ' . Document::class);
            $this->assertContains('Document Gama.pdf', Document::whereHasMorph('documentable', EmployeeDocument::class)->pluck('original_name')->toArray());
        });
    }
}
