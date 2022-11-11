<?php

namespace Tests\Feature;

use App\Events\ModelListed;
use App\Models\Bond;
use App\Models\Document;
use App\Models\DocumentType;
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
        parent::__construct();

        $this->service = new DocumentService(new DocumentRepository());
        $this->rightsService = new RightsDocumentService(new RightsDocumentRepository());
    }


    //setting up scenario for all tests
    public function setUp(): void
    {
        parent::setUp();

        Document::factory()->createOne(
            [
                'file_name' => 'Document Alpha.pdf',
                'documentable_id' => Document::factory()
                    ->createOne([
                        'bond_id' => Bond::factory()->createOne()->id,
                    ])->id,
                'documentable_type' => Document::class,
                'document_type_id' => DocumentType::factory()
                    ->createOne(
                        [
                            'name' => 'Type One',
                        ]
                    )->id,
            ]
        );

        Document::factory()->createOne(
            [
                'file_name' => 'Document Beta.pdf',
                'documentable_id' => Document::factory()
                    ->createOne([
                        'bond_id' => Bond::factory()->createOne()->id,
                    ])->id,
                'documentable_type' => Document::class,
                'document_type_id' => DocumentType::factory()
                    ->createOne(
                        [
                            'name' => 'Type Two',
                        ]
                    )->id,
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
                'documentable_id' => Document::factory()
                    ->createOne([
                        'bond_id' => Bond::factory()
                            ->createOne(
                                [
                                    'uaba_checked_at' => now(),
                                    'impediment' => false,
                                ]
                            )->id,
                    ])->id,
                'documentable_type' => Document::class,
                'document_type_id' => DocumentType::factory()
                    ->createOne(
                        [
                            'name' => 'Termo de cessão de direitos',
                        ]
                    )->id,
            ]
        );

        Event::fakeFor(function () {
            //execution
            $documents = $this->rightsService->list();

            //verifications
            Event::assertDispatched(ModelListed::class);
            $this->assertCount(1, $documents);
            $this->assertContains('Document Rights.pdf', Document::whereHasMorph('documentable', Document::class)->pluck('file_name')->toArray());
        });
    }

    /**
     * @test
     */
    public function documentShouldBeCreated(): void
    {
        //setting up scenario
        $attributes['fileName'] = (string) 'Document Gama.pdf';
        $attributes['fileData'] = (string) 'data:application/pdf;base64,';
        $attributes['documentTypeId'] = (string) 1;
        $attributes['referentId'] = (string) 1;

        $dto = new DocumentDto($attributes);

        Event::fakeFor(function () use ($dto) {
            //execution
            $this->service->create($dto);

            //verifications
            Event::assertDispatched('eloquent.created: ' . Document::class);
            $this->assertContains('Document Gama.pdf', Document::whereHasMorph('documentable', Document::class)->pluck('file_name')->toArray());
            $this->assertCount(3, Document::whereHasMorph('documentable', Document::class)->get());
            $this->assertCount(3, Document::all());
        });
    }

    /**
     * @test
     */
    public function documentShouldBePreparedToDownload()
    {
        Event::fakeFor(function () {
            //execution
            $document = $this->service->assembleDocument(1);

            //verifications
            Event::assertDispatched(ModelRead::class);
            $this->assertEquals('Document Employee Alpha.pdf', $document->get('name'));
        });
    }
}
