<?php

namespace Tests\Feature;

use App\Models\BondDocument;
use App\Models\Course;
use App\Models\Document;
use App\Models\DocumentType;
use App\Models\EmployeeDocument;
use App\Models\User;
use App\Models\UserType;
use App\Models\UserTypeAssignment;
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

    public function setUp(): void
    {
        parent::setUp();

        self::$userAdm = User::factory()->createOne(
            [
                'email' => 'adm_email@test.com',
            ]
        );

        /** @var UserType $userTypeAdm */
        $userTypeAdm = UserType::factory()->admin()->createOne();
        UserTypeAssignment::factory()->createOne([
            'user_id' => self::$userAdm->id,
            'user_type_id' => $userTypeAdm->id,
            'course_id' => null,
        ]);

        self::$userDir = User::factory()->createOne(
            [
                'email' => 'dir_email@test.com',
            ]
        );

        /** @var UserType $userTypeDir */
        $userTypeDir = UserType::factory()->director()->createOne();
        UserTypeAssignment::factory()->createOne([
            'user_id' => self::$userDir->id,
            'user_type_id' => $userTypeDir->id,
            'course_id' => null,
        ]);

        self::$userAss = User::factory()->createOne(
            [
                'email' => 'ass_email@test.com',
            ]
        );

        /** @var UserType $userTypeAss */
        $userTypeAss = UserType::factory()->assistant()->createOne();
        UserTypeAssignment::factory()->createOne([
            'user_id' => self::$userAss->id,
            'user_type_id' => $userTypeAss->id,
            'course_id' => null,
        ]);

        self::$userSec = User::factory()->createOne(
            [
                'email' => 'sec_email@test.com',
            ]
        );

        /** @var UserType $userTypeSec */
        $userTypeSec = UserType::factory()->secretary()->createOne();
        UserTypeAssignment::factory()->createOne([
            'user_id' => self::$userSec->id,
            'user_type_id' => $userTypeSec->id,
            'course_id' => null,
        ]);

        self::$userCoord = User::factory()->createOne(
            [
                'email' => 'coord_email@test.com',
            ]
        );

        /** @var UserType $userTypeCoord */
        $userTypeCoord = UserType::factory()->coordinator()->createOne();

        /** @var Course $courseCoord */
        $courseCoord = Course::factory()->createOne();
        UserTypeAssignment::factory()->createOne([
            'user_id' => self::$userCoord->id,
            'user_type_id' => $userTypeCoord->id,
            'course_id' => $courseCoord->id,
        ]);

        self::$userLdi = User::factory()->createOne(
            [
                'email' => 'ldi_email@test.com',
            ]
        );

        /** @var UserType $userTypeLdi */
        $userTypeLdi = UserType::factory()->ldi()->createOne();
        UserTypeAssignment::factory()->createOne([
            'user_id' => self::$userLdi->id,
            'user_type_id' => $userTypeLdi->id,
            'course_id' => null,
        ]);

        self::$userAlien = User::factory()->createOne(
            [
                'email' => 'alien_email@test.com',
            ]
        );

        /** @var UserType $userTypeAlien */
        $userTypeAlien = UserType::factory()->alien()->createOne();
        UserTypeAssignment::factory()->createOne([
            'user_id' => self::$userAlien->id,
            'user_type_id' => $userTypeAlien->id,
            'course_id' => null,
        ]);

        /** @var EmployeeDocument $employeeDocument */
        $employeeDocument = EmployeeDocument::factory()->createOne();

        /** @var BondDocument $bondDocument */
        $bondDocument = BondDocument::factory()->createOne();

        Document::factory()->createOne(
            [
                'original_name' => 'Document Employee Alpha.pdf',
                'documentable_id' => $employeeDocument->id,
                'documentable_type' => EmployeeDocument::class,
            ]
        );

        Document::factory()->createOne(
            [
                'original_name' => 'Document Bond Beta.pdf',
                'documentable_id' => $bondDocument->id,
                'documentable_type' => BondDocument::class,
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
    public function guestShouldntListEmployeesDocuments()
    {
        $this->get(route('employeesDocuments.index'))
            ->assertStatus(401);
    }

    /**
     * Authenticated user without permission Shouldnt list employees
     *
     * @return void
     *
     * @test
     */
    public function authenticatedUserWithoutPermissionShouldntListEmployeesDocuments()
    {
        $this->actingAs(self::$userAlien)
            ->withSession(['loggedInUser.currentUta' => null]);

        $this->get(route('employeesDocuments.index'))
            ->assertStatus(403);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     *
     * @test
     */
    public function administratorShouldListEmployeesDocuments()
    {
        $this->actingAs(self::$userAdm);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentUta' => $authUser->getFirstActiveResponsibility()]);

        $this->get(route('employeesDocuments.index'))
            ->assertSee('Document Employee Alpha.pdf')
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     *
     * @test
     */
    public function directorShouldListEmployeesDocuments()
    {
        $this->actingAs(self::$userDir);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentUta' => $authUser->getFirstActiveResponsibility()]);

        $this->get(route('employeesDocuments.index'))
            ->assertSee('Document Employee Alpha.pdf')
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     *
     * @test
     */
    public function assistantShouldListEmployeesDocuments()
    {
        $this->actingAs(self::$userAss);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentUta' => $authUser->getFirstActiveResponsibility()]);

        $this->get(route('employeesDocuments.index'))
            ->assertSee('Document Employee Alpha.pdf')
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     *
     * @test
     */
    public function secretaryShouldListEmployeesDocuments()
    {
        $this->actingAs(self::$userSec);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentUta' => $authUser->getFirstActiveResponsibility()]);

        $this->get(route('employeesDocuments.index'))
            ->assertSee('Document Employee Alpha.pdf')
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     *
     * @test
     */
    public function ldiShouldntListEmployeesDocuments()
    {
        $this->actingAs(self::$userLdi);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentUta' => $authUser->getFirstActiveResponsibility()]);

        $this->get(route('employeesDocuments.index'))
            ->assertStatus(403);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     *
     * @test
     */
    public function coordinatorShouldntListEmployeesDocuments()
    {
        $this->actingAs(self::$userCoord);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentUta' => $authUser->getFirstActiveResponsibility()]);

        $this->get(route('employeesDocuments.index'))
            ->assertStatus(403);
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
        $this->get(route('bondsDocuments.index'))
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
            ->withSession(['loggedInUser.currentUta' => null]);

        $this->get(route('bondsDocuments.index'))
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

        $this->withSession(['loggedInUser.currentUta' => $authUser->getFirstActiveResponsibility()]);

        $this->get(route('bondsDocuments.index'))
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

        $this->withSession(['loggedInUser.currentUta' => $authUser->getFirstActiveResponsibility()]);

        $this->get(route('bondsDocuments.index'))
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

        $this->withSession(['loggedInUser.currentUta' => $authUser->getFirstActiveResponsibility()]);

        $this->get(route('bondsDocuments.index'))
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

        $this->withSession(['loggedInUser.currentUta' => $authUser->getFirstActiveResponsibility()]);

        $this->get(route('bondsDocuments.index'))
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

        $this->withSession(['loggedInUser.currentUta' => $authUser->getFirstActiveResponsibility()]);

        $this->get(route('bondsDocuments.index'))
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

        $this->withSession(['loggedInUser.currentUta' => $authUser->getFirstActiveResponsibility()]);

        $this->get(route('bondsDocuments.index'))
            ->assertStatus(403);
    }


    // ================= See Employee Documents Tests =================

    /** @return Document */
    private function getEmployeeDocument(): Document
    {
        return Document::where('documentable_type', EmployeeDocument::class)->first();
    }

    /** @return array<string> */
    private function expectedDocumentContent(): array
    {
        return ['PDF', 'Test', 'EOF'];
    }

    /**
     * Guest Shouldnt access employee document
     *
     * @return void
     *
     * @test
     */
    public function guestShouldntAccessEmployeeDocument()
    {
        /** @var Document $document */
        $document = $this->getEmployeeDocument();
        $this->get(route('documents.show', ['id' => $document->id, 'htmlTitle' => $document->original_name]))
            ->assertStatus(401);
    }

    /**
     * Authenticated user without permission Shouldnt Access Employees document
     *
     * @return void
     *
     * @test
     */
    public function authenticatedUserWithoutPermissionShouldntAccessEmployeeDocument()
    {
        $this->actingAs(self::$userAlien)
            ->withSession(['loggedInUser.currentUta' => null]);

        /** @var Document $document */
        $document = $this->getEmployeeDocument();
        $this->get(route('documents.show', ['id' => $document->id, 'htmlTitle' => $document->original_name]))
            ->assertStatus(403);
    }

    /**
     * Admin user Should access employee document
     *
     * @return void
     *
     * @test
     */
    public function administratorShouldAccessEmployeeDocument()
    {
        $this->actingAs(self::$userAdm);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentUta' => $authUser->getFirstActiveResponsibility()]);

        /** @var Document $document */
        $document = $this->getEmployeeDocument();
        $this->get(route('documents.show', ['id' => $document->id, 'htmlTitle' => $document->original_name]))
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
    public function directorShouldAccessEmployeeDocument()
    {
        $this->actingAs(self::$userDir);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentUta' => $authUser->getFirstActiveResponsibility()]);

        /** @var Document $document */
        $document = $this->getEmployeeDocument();
        $this->get(route('documents.show', ['id' => $document->id, 'htmlTitle' => $document->original_name]))
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
    public function assistantShouldAccessEmployeeDocument()
    {
        $this->actingAs(self::$userAss);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentUta' => $authUser->getFirstActiveResponsibility()]);

        /** @var Document $document */
        $document = $this->getEmployeeDocument();
        $this->get(route('documents.show', ['id' => $document->id, 'htmlTitle' => $document->original_name]))
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
    public function secretaryShouldAccessEmployeeDocument()
    {
        $this->actingAs(self::$userSec);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentUta' => $authUser->getFirstActiveResponsibility()]);

        /** @var Document $document */
        $document = $this->getEmployeeDocument();
        $this->get(route('documents.show', ['id' => $document->id, 'htmlTitle' => $document->original_name]))
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
    public function ldiShouldntAccessEmployeeDocument()
    {
        $this->actingAs(self::$userLdi);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentUta' => $authUser->getFirstActiveResponsibility()]);

        /** @var Document $document */
        $document = $this->getEmployeeDocument();
        $this->get(route('documents.show', ['id' => $document->id, 'htmlTitle' => $document->original_name]))
            ->assertStatus(403);
    }

    /**
     * coordinator user Should access employee document
     *
     * @return void
     *
     * @test
     */
    public function coordinatorShouldntAccessEmployeeDocument()
    {
        $this->actingAs(self::$userCoord);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentUta' => $authUser->getFirstActiveResponsibility()]);

        /** @var Document $document */
        $document = $this->getEmployeeDocument();
        $this->get(route('documents.show', ['id' => $document->id, 'htmlTitle' => $document->original_name]))
            ->assertStatus(403);
    }


    // ================= See Bond Documents Tests =================

    /** @return Document */
    private function getBondDocument(): Document
    {
        return Document::where('documentable_type', BondDocument::class)->first();
    }

    /**
     * Guest Shouldnt access employee document
     *
     * @return void
     *
     * @test
     */
    public function guestShouldntAccessBondDocument()
    {
        /** @var Document $document */
        $document = $this->getBondDocument();
        $this->get(route('documents.show', ['id' => $document->id, 'htmlTitle' => $document->original_name]))
            ->assertStatus(401);
    }

    /**
     * Authenticated user without permission Shouldnt Access Employees document
     *
     * @return void
     *
     * @test
     */
    public function authenticatedUserWithoutPermissionShouldntAccessBondDocument()
    {
        $this->actingAs(self::$userAlien)
            ->withSession(['loggedInUser.currentUta' => null]);

        /** @var Document $document */
        $document = $this->getBondDocument();
        $this->get(route('documents.show', ['id' => $document->id, 'htmlTitle' => $document->original_name]))
            ->assertStatus(403);
    }

    /**
     * Admin user Should access employee document
     *
     * @return void
     *
     * @test
     */
    public function administratorShouldAccessBondDocument()
    {
        $this->actingAs(self::$userAdm);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentUta' => $authUser->getFirstActiveResponsibility()]);

        /** @var Document $document */
        $document = $this->getBondDocument();
        $this->get(route('documents.show', ['id' => $document->id, 'htmlTitle' => $document->original_name]))
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
    public function directorShouldAccessBondDocument()
    {
        $this->actingAs(self::$userDir);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentUta' => $authUser->getFirstActiveResponsibility()]);

        /** @var Document $document */
        $document = $this->getBondDocument();
        $this->get(route('documents.show', ['id' => $document->id, 'htmlTitle' => $document->original_name]))
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
    public function assistantShouldAccessBondDocument()
    {
        $this->actingAs(self::$userAss);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentUta' => $authUser->getFirstActiveResponsibility()]);

        /** @var Document $document */
        $document = $this->getBondDocument();
        $this->get(route('documents.show', ['id' => $document->id, 'htmlTitle' => $document->original_name]))
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
    public function secretaryShouldAccessBondDocument()
    {
        $this->actingAs(self::$userSec);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentUta' => $authUser->getFirstActiveResponsibility()]);

        /** @var Document $document */
        $document = $this->getBondDocument();
        $this->get(route('documents.show', ['id' => $document->id, 'htmlTitle' => $document->original_name]))
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
    public function ldiShouldntAccessBondDocument()
    {
        $this->actingAs(self::$userLdi);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentUta' => $authUser->getFirstActiveResponsibility()]);

        /** @var Document $document */
        $document = $this->getBondDocument();
        $this->get(route('documents.show', ['id' => $document->id, 'htmlTitle' => $document->original_name]))
            ->assertStatus(403);
    }

    /**
     * coordinator user Should access employee document
     *
     * @return void
     *
     * @test
     */
    public function coordinatorShouldntAccessBondDocument()
    {
        $this->actingAs(self::$userCoord);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentUta' => $authUser->getFirstActiveResponsibility()]);

        /** @var Document $document */
        $document = $this->getBondDocument();
        $this->get(route('documents.show', ['id' => $document->id, 'htmlTitle' => $document->original_name]))
            ->assertStatus(403);
    }


    // ================= See Create Employee Document Form Tests =================

    /** @return array<string> */
    private function expectedCreateEmployeeDocumentPageContent(): array
    {
        return ['Importar Documento de Colaborador', 'Tipo de Documento', 'Selecione o colaborador'];
    }

    /**
     * Guest Shouldnt access create employee document page
     *
     * @return void
     *
     * @test
     */
    public function guestShouldntAccessCreateEmployeeDocumentPage()
    {
        $this->get(route('employeesDocuments.create'))
            ->assertStatus(401);
    }

    /**
     * Authenticated user without permission Shouldnt Access create employee document page
     *
     * @return void
     *
     * @test
     */
    public function authenticatedUserWithoutPermissionShouldntAccessCreateEmployeeDocumentPage()
    {
        $this->actingAs(self::$userAlien)
            ->withSession(['loggedInUser.currentUta' => null]);

        $this->get(route('employeesDocuments.create'))
            ->assertStatus(403);
    }

    /**
     * Admin user Should access create employee document page
     *
     * @return void
     *
     * @test
     */
    public function administratorShouldAccessCreateEmployeeDocumentPage()
    {
        $this->actingAs(self::$userAdm);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentUta' => $authUser->getFirstActiveResponsibility()]);

        $this->get(route('employeesDocuments.create'))
            ->assertSee($this->expectedCreateEmployeeDocumentPageContent())
            ->assertStatus(200);
    }

    /**
     * director user Should access create employee document page
     *
     * @return void
     *
     * @test
     */
    public function directorShouldAccessCreateEmployeeDocumentPage()
    {
        $this->actingAs(self::$userDir);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentUta' => $authUser->getFirstActiveResponsibility()]);

        $this->get(route('employeesDocuments.create'))
            ->assertSee($this->expectedCreateEmployeeDocumentPageContent())
            ->assertStatus(200);
    }

    /**
     * assistant user Should access create employee document page
     *
     * @return void
     *
     * @test
     */
    public function assistantShouldAccessCreateEmployeeDocumentPage()
    {
        $this->actingAs(self::$userAss);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentUta' => $authUser->getFirstActiveResponsibility()]);

        $this->get(route('employeesDocuments.create'))
            ->assertSee($this->expectedCreateEmployeeDocumentPageContent())
            ->assertStatus(200);
    }

    /**
     * secretary user Should access create employee document page
     *
     * @return void
     *
     * @test
     */
    public function secretaryShouldAccessCreateEmployeeDocumentPage()
    {
        $this->actingAs(self::$userSec);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentUta' => $authUser->getFirstActiveResponsibility()]);

        $this->get(route('employeesDocuments.create'))
            ->assertSee($this->expectedCreateEmployeeDocumentPageContent())
            ->assertStatus(200);
    }

    /**
     * ldi user Should access create employee document page
     *
     * @return void
     *
     * @test
     */
    public function ldiShouldntAccessCreateEmployeeDocumentPage()
    {
        $this->actingAs(self::$userLdi);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentUta' => $authUser->getFirstActiveResponsibility()]);

        $this->get(route('employeesDocuments.create'))
            ->assertStatus(403);
    }

    /**
     * coordinator user Should access create employee document page
     *
     * @return void
     *
     * @test
     */
    public function coordinatorShouldntAccessCreateEmployeeDocumentPage()
    {
        $this->actingAs(self::$userCoord);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentUta' => $authUser->getFirstActiveResponsibility()]);

        $this->get(route('employeesDocuments.create'))
            ->assertStatus(403);
    }


    // ================= See Create Bond Document Form Tests =================

    /** @return array<string> */
    private function expectedCreateBondDocumentPageContent(): array
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
    public function guestShouldntAccessCreateBondDocumentPage()
    {
        $this->get(route('bondsDocuments.create'))
            ->assertStatus(401);
    }

    /**
     * Authenticated user without permission Shouldnt Access create bond document page
     *
     * @return void
     *
     * @test
     */
    public function authenticatedUserWithoutPermissionShouldntAccessCreateBondDocumentPage()
    {
        $this->actingAs(self::$userAlien)
            ->withSession(['loggedInUser.currentUta' => null]);

        $this->get(route('bondsDocuments.create'))
            ->assertStatus(403);
    }

    /**
     * Admin user Should access create bond document page
     *
     * @return void
     *
     * @test
     */
    public function administratorShouldAccessCreateBondDocumentPage()
    {
        $this->actingAs(self::$userAdm);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentUta' => $authUser->getFirstActiveResponsibility()]);

        $this->get(route('bondsDocuments.create'))
            ->assertSee($this->expectedCreateBondDocumentPageContent())
            ->assertStatus(200);
    }

    /**
     * director user Should access create bond document page
     *
     * @return void
     *
     * @test
     */
    public function directorShouldAccessCreateBondDocumentPage()
    {
        $this->actingAs(self::$userDir);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentUta' => $authUser->getFirstActiveResponsibility()]);

        $this->get(route('bondsDocuments.create'))
            ->assertSee($this->expectedCreateBondDocumentPageContent())
            ->assertStatus(200);
    }

    /**
     * assistant user Should access create bond document page
     *
     * @return void
     *
     * @test
     */
    public function assistantShouldAccessCreateBondDocumentPage()
    {
        $this->actingAs(self::$userAss);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentUta' => $authUser->getFirstActiveResponsibility()]);

        $this->get(route('bondsDocuments.create'))
            ->assertSee($this->expectedCreateBondDocumentPageContent())
            ->assertStatus(200);
    }

    /**
     * secretary user Should access create bond document page
     *
     * @return void
     *
     * @test
     */
    public function secretaryShouldAccessCreateBondDocumentPage()
    {
        $this->actingAs(self::$userSec);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentUta' => $authUser->getFirstActiveResponsibility()]);

        $this->get(route('bondsDocuments.create'))
            ->assertSee($this->expectedCreateBondDocumentPageContent())
            ->assertStatus(200);
    }

    /**
     * ldi user Should access create bond document page
     *
     * @return void
     *
     * @test
     */
    public function ldiShouldntAccessCreateBondDocumentPage()
    {
        $this->actingAs(self::$userLdi);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentUta' => $authUser->getFirstActiveResponsibility()]);

        $this->get(route('bondsDocuments.create'))
            ->assertStatus(403);
    }

    /**
     * coordinator user Should access create bond document page
     *
     * @return void
     *
     * @test
     */
    public function coordinatorShouldntAccessCreateBondDocumentPage()
    {
        $this->actingAs(self::$userCoord);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentUta' => $authUser->getFirstActiveResponsibility()]);

        $this->get(route('bondsDocuments.create'))
            ->assertStatus(403);
    }


    // ================= Create Employee Document Tests =================

    /**
     * @param ?string $documentClass
     * @param ?string $documentTypeId
     * @param ?string $referentId
     *
     * @return array<string, mixed>
     */
    private function createTestDocumentAttributes(?string $documentClass = EmployeeDocument::class, ?string $documentTypeId = null, ?string $referentId = '1'): array
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
        $requestAttributes[$documentClass::referentId()] = $referentId;

        return $requestAttributes;
    }

    /** @return array<string>  */
    private function expectedInfo(): array
    {
        return ['Document Gama.pdf', 'Test Doc Type'];
    }

    /**
     * Guest Shouldnt create employee document
     *
     * @return void
     *
     * @test
     */
    public function guestShouldntCreateEmployeeDocument()
    {
        $employeeDocumentArr = $this->createTestDocumentAttributes(EmployeeDocument::class);

        $this->post(route('employeesDocuments.store'), $employeeDocumentArr)
            ->assertStatus(401);
    }

    /**
     * Authenticated user without permission Shouldnt create employee document
     *
     * @return void
     *
     * @test
     */
    public function authenticatedUserWithoutPermissionShouldntCreateEmployeeDocument()
    {
        $employeeDocumentArr = $this->createTestDocumentAttributes(EmployeeDocument::class);

        $this->actingAs(self::$userAlien)
            ->withSession(['loggedInUser.currentUta' => null])
            ->followingRedirects()->post(route('employeesDocuments.store'), $employeeDocumentArr)
            ->assertStatus(403);
    }

    /**
     * Admin user Should create employees document
     *
     * @return void
     *
     * @test
     */
    public function administratorShouldCreateEmployeeDocument()
    {
        $this->actingAs(self::$userAdm);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentUta' => $authUser->getFirstActiveResponsibility()]);

        $employeeDocumentArr = $this->createTestDocumentAttributes(EmployeeDocument::class);

        $this->followingRedirects()->post(route('employeesDocuments.store'), $employeeDocumentArr)
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
    public function directorShouldCreateEmployeeDocument()
    {
        $this->actingAs(self::$userDir);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentUta' => $authUser->getFirstActiveResponsibility()]);

        $employeeDocumentArr = $this->createTestDocumentAttributes(EmployeeDocument::class);

        $this->followingRedirects()->post(route('employeesDocuments.store'), $employeeDocumentArr)
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
    public function assistantShouldCreateEmployeeDocument()
    {
        $this->actingAs(self::$userAss);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentUta' => $authUser->getFirstActiveResponsibility()]);

        $employeeDocumentArr = $this->createTestDocumentAttributes(EmployeeDocument::class);

        $this->followingRedirects()->post(route('employeesDocuments.store'), $employeeDocumentArr)
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
    public function secretaryShouldCreateEmployeeDocument()
    {
        $this->actingAs(self::$userSec);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentUta' => $authUser->getFirstActiveResponsibility()]);

        $employeeDocumentArr = $this->createTestDocumentAttributes(EmployeeDocument::class);

        $this->followingRedirects()->post(route('employeesDocuments.store'), $employeeDocumentArr)
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
    public function coordinatorShouldntCreateEmployeeDocument()
    {
        $this->actingAs(self::$userCoord);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentUta' => $authUser->getFirstActiveResponsibility()]);

        $employeeDocumentArr = $this->createTestDocumentAttributes(EmployeeDocument::class);

        $this->followingRedirects()->post(route('employeesDocuments.store'), $employeeDocumentArr)
            ->assertStatus(403);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     *
     * @test
     */
    public function ldiShouldntCreateEmployeeDocument()
    {
        $this->actingAs(self::$userLdi);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentUta' => $authUser->getFirstActiveResponsibility()]);

        $employeeDocumentArr = $this->createTestDocumentAttributes(EmployeeDocument::class);

        $this->followingRedirects()->post(route('employeesDocuments.store'), $employeeDocumentArr)
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
    public function guestShouldntCreateBondDocument()
    {
        $bondDocumentArr = $this->createTestDocumentAttributes(BondDocument::class);

        $this->post(route('bondsDocuments.store'), $bondDocumentArr)
            ->assertStatus(401);
    }

    /**
     * Authenticated user without permission Shouldnt create employee document
     *
     * @return void
     *
     * @test
     */
    public function authenticatedUserWithoutPermissionShouldntCreateBondDocument()
    {
        $bondDocumentArr = $this->createTestDocumentAttributes(BondDocument::class);

        $this->actingAs(self::$userAlien)
            ->withSession(['loggedInUser.currentUta' => null])
            ->followingRedirects()->post(route('bondsDocuments.store'), $bondDocumentArr)
            ->assertStatus(403);
    }

    /**
     * Admin user Should create employees document
     *
     * @return void
     *
     * @test
     */
    public function administratorShouldCreateBondDocument()
    {
        $this->actingAs(self::$userAdm);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentUta' => $authUser->getFirstActiveResponsibility()]);

        $bondDocumentArr = $this->createTestDocumentAttributes(BondDocument::class);

        $this->followingRedirects()->post(route('bondsDocuments.store'), $bondDocumentArr)
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
    public function directorShouldCreateBondDocument()
    {
        $this->actingAs(self::$userDir);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentUta' => $authUser->getFirstActiveResponsibility()]);

        $bondDocumentArr = $this->createTestDocumentAttributes(BondDocument::class);

        $this->followingRedirects()->post(route('bondsDocuments.store'), $bondDocumentArr)
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
    public function assistantShouldCreateBondDocument()
    {
        $this->actingAs(self::$userAss);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentUta' => $authUser->getFirstActiveResponsibility()]);

        $bondDocumentArr = $this->createTestDocumentAttributes(BondDocument::class);

        $this->followingRedirects()->post(route('bondsDocuments.store'), $bondDocumentArr)
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
    public function secretaryShouldCreateBondDocument()
    {
        $this->actingAs(self::$userSec);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentUta' => $authUser->getFirstActiveResponsibility()]);

        $bondDocumentArr = $this->createTestDocumentAttributes(BondDocument::class);

        $this->followingRedirects()->post(route('bondsDocuments.store'), $bondDocumentArr)
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
    public function coordinatorShouldntCreateBondDocument()
    {
        $this->actingAs(self::$userCoord);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentUta' => $authUser->getFirstActiveResponsibility()]);

        $bondDocumentArr = $this->createTestDocumentAttributes(BondDocument::class);

        $this->followingRedirects()->post(route('bondsDocuments.store'), $bondDocumentArr)
            ->assertStatus(403);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     *
     * @test
     */
    public function ldiShouldntCreateBondDocument()
    {
        $this->actingAs(self::$userLdi);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentUta' => $authUser->getFirstActiveResponsibility()]);

        $bondDocumentArr = $this->createTestDocumentAttributes(BondDocument::class);

        $this->followingRedirects()->post(route('bondsDocuments.store'), $bondDocumentArr)
            ->assertStatus(403);
    }


    // ================= Document Overwrite Tests =================

    /**
     * @return void
     *
     * @test
     */
    public function newEmployeeDocumentShouldOverwriteOldOne()
    {
        $originalDocument = Document::where('documentable_type', EmployeeDocument::class)->first();
        $originalName = $originalDocument->original_name;
        $originalDocTypeId = $originalDocument->document_type_id;
        $originalDocTypeName = DocumentType::find($originalDocTypeId)->name;
        $originalDocumentable = $originalDocument->documentable()->first();
        $originalReferentId = $originalDocumentable->pluck($originalDocumentable->referentId())->first();

        $this->actingAs(self::$userAdm);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentUta' => $authUser->getFirstActiveResponsibility()]);

        $this->get(route('employeesDocuments.index'))
            ->assertSee([$originalName, $originalDocTypeName])
            ->assertStatus(200);

        $employeeDocumentArr = $this->createTestDocumentAttributes(EmployeeDocument::class, (string) $originalDocTypeId, (string) $originalReferentId);

        $this->followingRedirects()->post(route('employeesDocuments.store'), $employeeDocumentArr)
            ->assertSee(['Document Gama.pdf', $originalDocTypeName])
            ->assertDontSee([$originalName])
            ->assertStatus(200);

        $this->assertDatabaseHas('documents', ['original_name' => $employeeDocumentArr['file']->name, 'document_type_id' => $originalDocTypeId, 'documentable_type' => EmployeeDocument::class]);
    }

    /**
     * @return void
     *
     * @test
     */
    public function newBondDocumentShouldOverwriteOldOne()
    {
        $originalDocument = Document::where('documentable_type', BondDocument::class)->first();
        $originalName = $originalDocument->original_name;
        $originalDocTypeId = $originalDocument->document_type_id;
        $originalDocTypeName = DocumentType::find($originalDocTypeId)->name;
        $originalDocumentable = $originalDocument->documentable()->first();
        $originalReferentId = $originalDocumentable->pluck($originalDocumentable->referentId())->first();

        $this->actingAs(self::$userAdm);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentUta' => $authUser->getFirstActiveResponsibility()]);

        $this->get(route('bondsDocuments.index'))
            ->assertSee([$originalName, $originalDocTypeName])
            ->assertStatus(200);

        $bondDocumentArr = $this->createTestDocumentAttributes(BondDocument::class, (string) $originalDocTypeId, (string) $originalReferentId);

        $this->followingRedirects()->post(route('bondsDocuments.store'), $bondDocumentArr)
            ->assertSee(['Document Gama.pdf', $originalDocTypeName])
            ->assertDontSee([$originalName])
            ->assertStatus(200);

        $this->assertDatabaseHas('documents', ['original_name' => $bondDocumentArr['file']->name, 'document_type_id' => $originalDocTypeId, 'documentable_type' => BondDocument::class]);
    }
}
