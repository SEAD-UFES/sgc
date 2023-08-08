<?php

namespace Tests\Feature;

use App\Models\Bond;
use App\Models\Course;
use App\Models\Document;
use App\Models\DocumentType;
use App\Models\User;
use App\Models\UserType;
use App\Models\Responsibility;
use App\Repositories\ResponsibilityRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class DocumentTest extends TestCase
{
    use RefreshDatabase;

    private static User $userAdm;
    private static User $userDir;
    private static User $userAss;
    private static User $userSec;
    private static User $userCoord;
    private static User $userLdi;
    private static User $userAlien;

    private ResponsibilityRepository $responsibilityRepository;

    public function __construct()
    {
        parent::__construct('DocumentTest');

        $this->responsibilityRepository = new ResponsibilityRepository();
    }

    public function setUp(): void
    {
        parent::setUp();

        User::factory()->withoutEmployee()->create([
            'login' => 'sgc_system',
        ]);

        self::$userAdm = User::factory()->createOne(
            [
                'login' => 'adm_email@test.com',
            ]
        );

        /** @var UserType $userTypeAdm */
        $userTypeAdm = UserType::factory()->admin()->createOne();
        Responsibility::factory()->createOne([
            'user_id' => self::$userAdm->id,
            'user_type_id' => $userTypeAdm->id,
            'course_id' => null,
        ]);

        self::$userDir = User::factory()->createOne(
            [
                'login' => 'dir_email@test.com',
            ]
        );

        /** @var UserType $userTypeDir */
        $userTypeDir = UserType::factory()->director()->createOne();
        Responsibility::factory()->createOne([
            'user_id' => self::$userDir->id,
            'user_type_id' => $userTypeDir->id,
            'course_id' => null,
        ]);

        self::$userAss = User::factory()->createOne(
            [
                'login' => 'ass_email@test.com',
            ]
        );

        /** @var UserType $userTypeAss */
        $userTypeAss = UserType::factory()->assistant()->createOne();
        Responsibility::factory()->createOne([
            'user_id' => self::$userAss->id,
            'user_type_id' => $userTypeAss->id,
            'course_id' => null,
        ]);

        self::$userSec = User::factory()->createOne(
            [
                'login' => 'sec_email@test.com',
            ]
        );

        /** @var UserType $userTypeSec */
        $userTypeSec = UserType::factory()->secretary()->createOne();
        Responsibility::factory()->createOne([
            'user_id' => self::$userSec->id,
            'user_type_id' => $userTypeSec->id,
            'course_id' => null,
        ]);

        self::$userCoord = User::factory()->createOne(
            [
                'login' => 'coord_email@test.com',
            ]
        );

        /** @var UserType $userTypeCoord */
        $userTypeCoord = UserType::factory()->coordinator()->createOne();

        /** @var Course $courseCoord */
        $courseCoord = Course::factory()->createOne();
        Responsibility::factory()->createOne([
            'user_id' => self::$userCoord->id,
            'user_type_id' => $userTypeCoord->id,
            'course_id' => $courseCoord->id,
        ]);

        self::$userLdi = User::factory()->createOne(
            [
                'login' => 'ldi_email@test.com',
            ]
        );

        /** @var UserType $userTypeLdi */
        $userTypeLdi = UserType::factory()->ldi()->createOne();
        Responsibility::factory()->createOne([
            'user_id' => self::$userLdi->id,
            'user_type_id' => $userTypeLdi->id,
            'course_id' => null,
        ]);

        self::$userAlien = User::factory()->createOne(
            [
                'login' => 'alien_email@test.com',
            ]
        );

        /** @var UserType $userTypeAlien */
        $userTypeAlien = UserType::factory()->alien()->createOne();
        Responsibility::factory()->createOne([
            'user_id' => self::$userAlien->id,
            'user_type_id' => $userTypeAlien->id,
            'course_id' => null,
        ]);

        Document::factory()->createOne(
            [
                'file_name' => 'Document Bond Beta.pdf',
                'related_id' => Bond::factory()->createOne()->getAttribute('id'),
                'related_type' => Bond::class,
            ]
        );
    }

    /**
     * A basic feature test example.
     *
     * @return void
     *
     * @test
     */
    public function guestShouldntListBondsDocuments()
    {
        $this->get(route('documents.index'))
            ->assertStatus(401);
    }

    /**
     * Authenticated user without permission Shouldnt list employees
     *
     * @return void
     *
     * @test
     */
    public function authenticatedUserWithoutPermissionShouldntListBondsDocuments()
    {
        $this->actingAs(self::$userAlien)
            ->withSession(['loggedInUser.currentResponsibility' => null]);

        $this->get(route('documents.index'))
            ->assertStatus(403);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     *
     * @test
     */
    public function administratorShouldListBondsDocuments()
    {
        $this->actingAs(self::$userAdm);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        $this->get(route('documents.index'))
            ->assertSee('Document Bond Beta.pdf')
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     *
     * @test
     */
    public function directorShouldListBondsDocuments()
    {
        $this->actingAs(self::$userDir);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        $this->get(route('documents.index'))
            ->assertSee('Document Bond Beta.pdf')
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     *
     * @test
     */
    public function assistantShouldListBondsDocuments()
    {
        $this->actingAs(self::$userAss);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        $this->get(route('documents.index'))
            ->assertSee('Document Bond Beta.pdf')
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     *
     * @test
     */
    public function secretaryShouldListBondsDocuments()
    {
        $this->actingAs(self::$userSec);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        $this->get(route('documents.index'))
            ->assertSee('Document Bond Beta.pdf')
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     *
     * @test
     */
    public function ldiShouldntListBondsDocuments()
    {
        $this->actingAs(self::$userLdi);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        $this->get(route('documents.index'))
            ->assertStatus(403);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     *
     * @test
     */
    public function coordinatorShouldntListBondsDocuments()
    {
        $this->actingAs(self::$userCoord);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        $this->get(route('documents.index'))
            ->assertStatus(403);
    }

    // ================= See Bond Documents Tests =================

    /** @return Document */
    private function getDocument(): Document
    {
        return Document::where('related_type', Bond::class)->first();
    }

    /**
     * Guest Shouldnt access employee document
     *
     * @return void
     *
     * @test
     */
    public function guestShouldntAccessDocument()
    {
        /** @var Document $document */
        $document = $this->getDocument();
        $this->get(route('documents.show', ['id' => $document->id, 'htmlTitle' => $document->file_name]))
            ->assertStatus(401);
    }

    /**
     * Authenticated user without permission Shouldnt Access Employees document
     *
     * @return void
     *
     * @test
     */
    public function authenticatedUserWithoutPermissionShouldntAccessDocument()
    {
        $this->actingAs(self::$userAlien)
            ->withSession(['loggedInUser.currentResponsibility' => null]);

        /** @var Document $document */
        $document = $this->getDocument();
        $this->get(route('documents.show', ['id' => $document->id, 'htmlTitle' => $document->file_name]))
            ->assertStatus(403);
    }

    /**
     * Admin user Should access employee document
     *
     * @return void
     *
     * @test
     */
    public function administratorShouldAccessDocument()
    {
        $this->actingAs(self::$userAdm);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        /** @var Document $document */
        $document = $this->getDocument();
        $this->get(route('documents.show', ['id' => $document->id, 'htmlTitle' => $document->file_name]))
            ->assertSee($this->expectedDocumentContent())
            ->assertStatus(200);
    }

    /**
     * director user Should access employee document
     *
     * @return void
     *
     * @test
     */
    public function directorShouldAccessDocument()
    {
        $this->actingAs(self::$userDir);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        /** @var Document $document */
        $document = $this->getDocument();
        $this->get(route('documents.show', ['id' => $document->id, 'htmlTitle' => $document->file_name]))
            ->assertSee($this->expectedDocumentContent())
            ->assertStatus(200);
    }

    /**
     * assistant user Should access employee document
     *
     * @return void
     *
     * @test
     */
    public function assistantShouldAccessDocument()
    {
        $this->actingAs(self::$userAss);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        /** @var Document $document */
        $document = $this->getDocument();
        $this->get(route('documents.show', ['id' => $document->id, 'htmlTitle' => $document->file_name]))
            ->assertSee($this->expectedDocumentContent())
            ->assertStatus(200);
    }

    /**
     * secretary user Should access employee document
     *
     * @return void
     *
     * @test
     */
    public function secretaryShouldAccessDocument()
    {
        $this->actingAs(self::$userSec);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        /** @var Document $document */
        $document = $this->getDocument();
        $this->get(route('documents.show', ['id' => $document->id, 'htmlTitle' => $document->file_name]))
            ->assertSee($this->expectedDocumentContent())
            ->assertStatus(200);
    }

    /**
     * ldi user Should access employee document
     *
     * @return void
     *
     * @test
     */
    public function ldiShouldntAccessDocument()
    {
        $this->actingAs(self::$userLdi);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        /** @var Document $document */
        $document = $this->getDocument();
        $this->get(route('documents.show', ['id' => $document->id, 'htmlTitle' => $document->file_name]))
            ->assertStatus(403);
    }

    /**
     * coordinator user Should access employee document
     *
     * @return void
     *
     * @test
     */
    public function coordinatorShouldntAccessDocument()
    {
        $this->actingAs(self::$userCoord);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        /** @var Document $document */
        $document = $this->getDocument();
        $this->get(route('documents.show', ['id' => $document->id, 'htmlTitle' => $document->file_name]))
            ->assertStatus(403);
    }


    // ================= See Create Bond Document Form Tests =================

    /** @return array<string> */
    private function expectedCreateDocumentPageContent(): array
    {
        return ['Importar Documento de Vínculo', 'Tipo de Documento', 'Vínculo'];
    }

    /**
     * Guest Shouldnt access create bond document page
     *
     * @return void
     *
     * @test
     */
    public function guestShouldntAccessCreateDocumentPage()
    {
        $this->get(route('documents.create'))
            ->assertStatus(401);
    }

    /**
     * Authenticated user without permission Shouldnt Access create bond document page
     *
     * @return void
     *
     * @test
     */
    public function authenticatedUserWithoutPermissionShouldntAccessCreateDocumentPage()
    {
        $this->actingAs(self::$userAlien)
            ->withSession(['loggedInUser.currentResponsibility' => null]);

        $this->get(route('documents.create'))
            ->assertStatus(403);
    }

    /**
     * Admin user Should access create bond document page
     *
     * @return void
     *
     * @test
     */
    public function administratorShouldAccessCreateDocumentPage()
    {
        $this->actingAs(self::$userAdm);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        $this->get(route('documents.create'))
            ->assertSee($this->expectedCreateDocumentPageContent())
            ->assertStatus(200);
    }

    /**
     * director user Should access create bond document page
     *
     * @return void
     *
     * @test
     */
    public function directorShouldAccessCreateDocumentPage()
    {
        $this->actingAs(self::$userDir);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        $this->get(route('documents.create'))
            ->assertSee($this->expectedCreateDocumentPageContent())
            ->assertStatus(200);
    }

    /**
     * assistant user Should access create bond document page
     *
     * @return void
     *
     * @test
     */
    public function assistantShouldAccessCreateDocumentPage()
    {
        $this->actingAs(self::$userAss);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        $this->get(route('documents.create'))
            ->assertSee($this->expectedCreateDocumentPageContent())
            ->assertStatus(200);
    }

    /**
     * secretary user Should access create bond document page
     *
     * @return void
     *
     * @test
     */
    public function secretaryShouldAccessCreateDocumentPage()
    {
        $this->actingAs(self::$userSec);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        $this->get(route('documents.create'))
            ->assertSee($this->expectedCreateDocumentPageContent())
            ->assertStatus(200);
    }

    /**
     * ldi user Should access create bond document page
     *
     * @return void
     *
     * @test
     */
    public function ldiShouldntAccessCreateDocumentPage()
    {
        $this->actingAs(self::$userLdi);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        $this->get(route('documents.create'))
            ->assertStatus(403);
    }

    /**
     * coordinator user Should access create bond document page
     *
     * @return void
     *
     * @test
     */
    public function coordinatorShouldntAccessCreateDocumentPage()
    {
        $this->actingAs(self::$userCoord);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        $this->get(route('documents.create'))
            ->assertStatus(403);
    }


    // ================= Create Bond Document Tests =================

    /**
     * Guest Shouldnt create employee document
     *
     * @return void
     *
     * @test
     */
    public function guestShouldntCreateDocument()
    {
        $bondDocumentArr = $this->createTestDocumentAttributes();

        $this->post(route('documents.store'), $bondDocumentArr)
            ->assertStatus(401);
    }

    /**
     * Authenticated user without permission Shouldnt create employee document
     *
     * @return void
     *
     * @test
     */
    public function authenticatedUserWithoutPermissionShouldntCreateDocument()
    {
        $bondDocumentArr = $this->createTestDocumentAttributes();

        $this->actingAs(self::$userAlien)
            ->withSession(['loggedInUser.currentResponsibility' => null])
            ->followingRedirects()->post(route('documents.store'), $bondDocumentArr)
            ->assertStatus(403);
    }

    /**
     * Admin user Should create employees document
     *
     * @return void
     *
     * @test
     */
    public function administratorShouldCreateDocument()
    {
        $this->actingAs(self::$userAdm);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        $bondDocumentArr = $this->createTestDocumentAttributes();

        $this->followingRedirects()->post(route('documents.store'), $bondDocumentArr)
            ->assertSee($this->expectedInfo())
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     *
     * @test
     */
    public function directorShouldCreateDocument()
    {
        $this->actingAs(self::$userDir);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        $bondDocumentArr = $this->createTestDocumentAttributes();

        $this->followingRedirects()->post(route('documents.store'), $bondDocumentArr)
            ->assertSee($this->expectedInfo())
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     *
     * @test
     */
    public function assistantShouldCreateDocument()
    {
        $this->actingAs(self::$userAss);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        $bondDocumentArr = $this->createTestDocumentAttributes();

        $this->followingRedirects()->post(route('documents.store'), $bondDocumentArr)
            ->assertSee($this->expectedInfo())
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     *
     * @test
     */
    public function secretaryShouldCreateDocument()
    {
        $this->actingAs(self::$userSec);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        $bondDocumentArr = $this->createTestDocumentAttributes();

        $this->followingRedirects()->post(route('documents.store'), $bondDocumentArr)
            ->assertSee($this->expectedInfo())
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     *
     * @test
     */
    public function coordinatorShouldntCreateDocument()
    {
        $this->actingAs(self::$userCoord);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        $bondDocumentArr = $this->createTestDocumentAttributes();

        $this->followingRedirects()->post(route('documents.store'), $bondDocumentArr)
            ->assertStatus(403);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     *
     * @test
     */
    public function ldiShouldntCreateDocument()
    {
        $this->actingAs(self::$userLdi);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        $bondDocumentArr = $this->createTestDocumentAttributes();

        $this->followingRedirects()->post(route('documents.store'), $bondDocumentArr)
            ->assertStatus(403);
    }

    /** @return array<string> */
    private function expectedDocumentContent(): array
    {
        return ['PDF', 'Test', 'EOF'];
    }

    /**
     * @param string $documentTypeId
     * @param string $referentId
     *
     * @return array<string, mixed>
     */
    private function createTestDocumentAttributes(string $documentTypeId = null, string $referentId = '1'): array
    {
        /** @var UploadedFile $testDocumentFile */
        $testDocumentFile = UploadedFile::fake()->create((debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2)[1]['function']) . '_Document Gama.pdf', 20, 'application/pdf');

        /** @var DocumentType $testDocumentType */
        $testDocumentType = DocumentType::factory()->createOne(
            [
                'name' => 'Test Doc Type',
            ]
        );

        $requestAttributes = [];
        $requestAttributes['file'] = $testDocumentFile;
        $requestAttributes['document_type_id'] = $documentTypeId ?? $testDocumentType->id;
        $requestAttributes['bond_id'] = $referentId;

        return $requestAttributes;
    }

    /** @return array<string>  */
    private function expectedInfo(): array
    {
        return ['Document Gama.pdf', 'Test Doc Type'];
    }
}
