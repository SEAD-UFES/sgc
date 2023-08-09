<?php

namespace Tests\Feature;

use App\Events\ModelListed;
use App\Events\ModelRead;
use App\Models\Bond;
use App\Models\Document;
use App\Models\DocumentType;
use App\Models\User;
use App\Repositories\DocumentRepository;
use App\Repositories\RightsDocumentRepository;
use App\Services\DocumentService;
use App\Services\Dto\DocumentDto;
use App\Services\RightsDocumentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class DocumentServiceTest extends TestCase
{
    use RefreshDatabase;

    private DocumentService $service;
    private RightsDocumentService $rightsService;

    /** @return void  */
    public function __construct()
    {
        parent::__construct('DocumentServiceTest');

        $this->service = new DocumentService(new DocumentRepository());
        $this->rightsService = new RightsDocumentService(new RightsDocumentRepository());
    }


    //setting up scenario for all tests
    protected function setUp(): void
    {
        parent::setUp();

        Document::factory()->createOne(
            [
                'file_name' => 'Document Alpha.pdf',
                'related_id' => Bond::factory()->createOne()->getAttribute('id'),
                'related_type' => Bond::class,
                'document_type_id' => DocumentType::factory()
                    ->createOne(
                        [
                            'name' => 'Type One',
                        ]
                    )->getAttribute('id'),
            ]
        );

        Document::factory()->createOne(
            [
                'file_name' => 'Document Beta.pdf',
                'related_id' => Bond::factory()->createOne()->getAttribute('id'),
                'related_type' => Bond::class,
                'document_type_id' => DocumentType::factory()
                    ->createOne(
                        [
                            'name' => 'Type Two',
                        ]
                    )->getAttribute('id'),
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
            $documents = $this->service->list(null, null);

            //verifications
            Event::assertDispatched(ModelListed::class);
            $this->assertCount(2, $documents);
            $this->assertContains('Document Alpha.pdf', $documents->pluck('file_name')->toArray());
            $this->assertContains('Document Beta.pdf', $documents->pluck('file_name')->toArray());
        });
    }

    /**
     * @test
     */
    public function rightsShouldBeListed(): void
    {
        Document::factory()->createOne(
            [
                'file_name' => 'Document Rights.pdf',
                'related_id' => Bond::factory()
                    ->createOne()->getAttribute('id'),
                'related_type' => Bond::class,
                'document_type_id' => DocumentType::factory()
                    ->createOne(
                        [
                            'name' => 'Termo de cessão de direitos',
                        ]
                    )->getAttribute('id'),
            ]
        );

        Event::fakeFor(function () {
            //execution
            $documents = $this->rightsService->list(null, null);

            //verifications
            Event::assertDispatched(ModelListed::class);
            $this->assertCount(1, $documents);
            $this->assertContains('Document Rights.pdf', Document::whereHasMorph('related', Bond::class)->pluck('file_name')->toArray());
        });
    }

    /**
     * @test
     */
    public function documentShouldBeCreated(): void
    {
        //setting up scenario
        User::factory()->withoutEmployee()->create([
            'login' => 'sgc_system',
        ]);

        $dto = new DocumentDto(
            fileName: 'Document Gama.pdf',
            fileData: 'data:application/pdf;base64,',
            documentTypeId: intval(DocumentType::factory()
                ->createOne(
                    [
                        'name' => 'Tipo Gama',
                    ]
                )->getAttribute('id')),
            relatedId: 1,
        );

        Event::fakeFor(function () use ($dto) {
            //execution
            $this->service->create($dto);


            //verifications
            Event::assertDispatched('eloquent.created: ' . Document::class);
            $this->assertContains('Document Gama.pdf', Document::whereHasMorph('related', Bond::class)->pluck('file_name')->toArray());
            $this->assertCount(3, Document::whereHasMorph('related', Bond::class)->get());
            $this->assertCount(3, Document::all());
        });
    }

    /**
     * @test
     */
    public function documentShouldBePreparedToDownload(): void
    {
        Event::fakeFor(function () {
            //execution
            $document = $this->service->assembleDocument(1);

            //verifications
            Event::assertDispatched(ModelRead::class);
            $this->assertEquals('Document Alpha.pdf', $document->get('name'));
        });
    }
}
