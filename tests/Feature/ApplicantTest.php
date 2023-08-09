<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use App\Enums\CallStates;
use App\Models\Applicant;
use App\Models\Course;
use App\Models\Pole;
use App\Models\Role;
use App\Models\User;
use App\Models\UserType;
use App\Models\Responsibility;
use App\Repositories\ResponsibilityRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Tests\TestCase;

class ApplicantTest extends TestCase
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
        parent::__construct('ApplicantTest');

        $this->responsibilityRepository = new ResponsibilityRepository();
    }

    protected function setUp(): void
    {
        parent::setUp();

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

        Applicant::factory()->createOne(
            [
                'name' => 'John Doe',
                'email' => 'john@test.com',
                'area_code' => '01',
                'landline' => '12345678',
                'mobile' => '123456789',
                'hiring_process' => '001',
            ]
        );

        Applicant::factory()->createOne(
            [
                'name' => 'Jane Doe',
                'email' => 'jane@othertest.com',
                'area_code' => '02',
                'landline' => '01234567',
                'mobile' => '012345678',
                'hiring_process' => '002',
            ]
        );
    }


    // ================= See Applicants list Tests =================

    /**
     * Guest Shouldnt list applicants.
     *
     * @return void
     */
    #[Test]
    public function guestShouldntListApplicants()
    {
        $this->get(route('applicants.index'))
            ->assertStatus(401);
    }

    /**
     * Authenticated user without permission Shouldnt list applicants
     *
     * @return void
     */
    #[Test]
    public function authenticatedUserWithoutPermissionShouldntListApplicants()
    {
        $this->actingAs(self::$userAlien)
            ->withSession(['loggedInUser.currentResponsibility' => null]);

        $this->get(route('applicants.index'))
            ->assertStatus(403);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    #[Test]
    public function administratorShouldListApplicants()
    {
        $this->actingAs(self::$userAdm);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        $this->get(route('applicants.index'))
            ->assertSee(['John Doe', 'Jane Doe', 'john@test.com', 'jane@othertest.com'])
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    #[Test]
    public function directorShouldListApplicants()
    {
        $this->actingAs(self::$userDir);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        $this->get(route('applicants.index'))
            ->assertSee(['John Doe', 'Jane Doe', 'john@test.com', 'jane@othertest.com'])
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    #[Test]
    public function assistantShouldntListApplicants()
    {
        $this->actingAs(self::$userAss);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        $this->get(route('applicants.index'))
            ->assertStatus(403);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    #[Test]
    public function secretaryShouldListApplicants()
    {
        $this->actingAs(self::$userSec);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        $this->get(route('applicants.index'))
            ->assertSee(['John Doe', 'Jane Doe', 'john@test.com', 'jane@othertest.com'])
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    #[Test]
    public function ldiShouldntListApplicants()
    {
        $this->actingAs(self::$userLdi);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        $this->get(route('applicants.index'))
            ->assertStatus(403);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    #[Test]
    public function coordinatorShouldListApplicants()
    {
        $this->actingAs(self::$userCoord);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        $this->get(route('applicants.index'))
            ->assertSee(['John Doe', 'Jane Doe', 'john@test.com', 'jane@othertest.com'])
            ->assertStatus(200);
    }


    // ================= See Create Form Tests =================


    /**
     * Guest Shouldnt access create applicant page
     *
     * @return void
     */
    #[Test]
    public function guestShouldntAccessCreateApplicantPage()
    {
        $this->get(route('applicants.create'))
            ->assertStatus(401);
    }

    /**
     * Authenticated user without permission Shouldnt Access create applicant page
     *
     * @return void
     */
    #[Test]
    public function authenticatedUserWithoutPermissionShouldntAccessCreateApplicantPage()
    {
        $this->actingAs(self::$userAlien)
            ->withSession(['loggedInUser.currentResponsibility' => null]);

        $this->get(route('applicants.create'))
            ->assertStatus(403);
    }

    /**
     * Admin user Should access create applicant page
     *
     * @return void
     */
    #[Test]
    public function administratorShouldAccessCreateApplicantPage()
    {
        $this->actingAs(self::$userAdm);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        $this->get(route('applicants.create'))
            ->assertSee(['Cadastrar Aprovado', 'Telefone'])
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    #[Test]
    public function directorShouldAccessCreateApplicantPage()
    {
        $this->actingAs(self::$userDir);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        $this->get(route('applicants.create'))
            ->assertSee(['Cadastrar Aprovado', 'Telefone'])
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    #[Test]
    public function assistantShouldntAccessCreateApplicantPage()
    {
        $this->actingAs(self::$userAss);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        $this->get(route('applicants.create'))
            ->assertStatus(403);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    #[Test]
    public function secretaryShouldAccessCreateApplicantPage()
    {
        $this->actingAs(self::$userSec);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        $this->get(route('applicants.create'))
            ->assertSee(['Cadastrar Aprovado', 'Telefone'])
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    #[Test]
    public function ldiShouldntAccessCreateApplicantPage()
    {
        $this->actingAs(self::$userLdi);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        $this->get(route('applicants.create'))
            ->assertStatus(403);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    #[Test]
    public function coordinatorShouldntAccessCreateApplicantPage()
    {
        $this->actingAs(self::$userCoord);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        $this->get(route('applicants.create'))
            ->assertStatus(403);
    }


    // ================= See Create Step 1 Form Tests =================

    /**
     * Guest Shouldnt access create applicant page
     *
     * @return void
     */
    #[Test]
    public function guestShouldntAccessStepOneCreateApplicantsPage()
    {
        $this->get(route('applicants.create_many.step_1'))
            ->assertStatus(401);
    }

    /**
     * Authenticated user without permission Shouldnt Access create applicant page
     *
     * @return void
     */
    #[Test]
    public function authenticatedUserWithoutPermissionShouldntAccessStepOneCreateApplicantsPage()
    {
        $this->actingAs(self::$userAlien)
            ->withSession(['loggedInUser.currentResponsibility' => null]);

        $this->get(route('applicants.create_many.step_1'))
            ->assertStatus(403);
    }

    /**
     * Admin user Should access create applicant page
     *
     * @return void
     */
    #[Test]
    public function administratorShouldAccessStepOneCreateApplicantsPage()
    {
        $this->actingAs(self::$userAdm);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        $this->get(route('applicants.create_many.step_1'))
            ->assertSee(['Importar Aprovados', 'Enviar arquivo'])
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    #[Test]
    public function directorShouldAccessStepOneCreateApplicantsPage()
    {
        $this->actingAs(self::$userDir);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        $this->get(route('applicants.create_many.step_1'))
            ->assertSee(['Importar Aprovados', 'Enviar arquivo'])
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    #[Test]
    public function assistantShouldntAccessStepOneCreateApplicantsPage()
    {
        $this->actingAs(self::$userAss);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        $this->get(route('applicants.create_many.step_1'))
            ->assertStatus(403);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    #[Test]
    public function secretaryShouldAccessStepOneCreateApplicantsPage()
    {
        $this->actingAs(self::$userSec);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        $this->get(route('applicants.create_many.step_1'))
            ->assertSee(['Importar Aprovados', 'Enviar arquivo'])
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    #[Test]
    public function ldiShouldntAccessStepOneCreateApplicantsPage()
    {
        $this->actingAs(self::$userLdi);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        $this->get(route('applicants.create_many.step_1'))
            ->assertStatus(403);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    #[Test]
    public function coordinatorShouldntAccessStepOneCreateApplicantsPage()
    {
        $this->actingAs(self::$userCoord);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        $this->get(route('applicants.create_many.step_1'))
            ->assertStatus(403);
    }


    // ================= See Create Step 2 Form Tests =================

    /**
     * @return array<Applicant>
     */
    private function createTestImportedApplicants(): array
    {
        /** @var array<Applicant> $importedApplicants */
        $importedApplicants = [];

        /** @var Applicant $newApplicant1 $ */
        $newApplicant1 = Applicant::factory()->makeOne(
            [
                'name' => 'Carl Doe',
                'email' => (debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2)[1]['function']) . '_1@test-case.com',
            ]
        );

        /** @var Applicant $newApplicant2 $ */
        $newApplicant2 = Applicant::factory()->makeOne(
            [
                'name' => 'Doug Doe',
                'email' => (debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2)[1]['function']) . '_2@test-case.com',
            ]
        );

        array_push($importedApplicants, $newApplicant1, $newApplicant2);

        return $importedApplicants;
    }

    /**
     * Guest Shouldnt access create applicant page
     *
     * @return void
     */
    #[Test]
    public function guestShouldntAccessStepTwoCreateApplicantsPage()
    {
        $this->get(route('applicants.create_many.step_2'))
            ->assertStatus(401);
    }

    /**
     * Authenticated user without permission Shouldnt Access create applicant page
     *
     * @return void
     */
    #[Test]
    public function authenticatedUserWithoutPermissionShouldntAccessStepTwoCreateApplicantsPage()
    {
        $this->actingAs(self::$userAlien)
            ->withSession(['loggedInUser.currentResponsibility' => null, 'importedApplicants' => $this->createTestImportedApplicants()]);

        $this->get(route('applicants.create_many.step_2'))
            ->assertStatus(403);
    }

    /**
     * Admin user Should access create applicant page
     *
     * @return void
     */
    #[Test]
    public function administratorShouldAccessStepTwoCreateApplicantsPage()
    {
        $this->actingAs(self::$userAdm);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id'))), 'importedApplicants' => $this->createTestImportedApplicants()]);

        $this->get(route('applicants.create_many.step_2'))
            ->assertSee(['Revisão de Importação', 'Importar', 'Carl Doe', 'Doug Doe'])
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    #[Test]
    public function directorShouldAccessStepTwoCreateApplicantsPage()
    {
        $this->actingAs(self::$userDir);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id'))), 'importedApplicants' => $this->createTestImportedApplicants()]);

        $this->get(route('applicants.create_many.step_2'))
            ->assertSee(['Revisão de Importação', 'Importar', 'Carl Doe', 'Doug Doe'])
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    #[Test]
    public function assistantShouldntAccessStepTwoCreateApplicantsPage()
    {
        $this->actingAs(self::$userAss);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id'))), 'importedApplicants' => $this->createTestImportedApplicants()]);

        $this->get(route('applicants.create_many.step_2'))
            ->assertStatus(403);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    #[Test]
    public function secretaryShouldAccessStepTwoCreateApplicantsPage()
    {
        $this->actingAs(self::$userSec);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id'))), 'importedApplicants' => $this->createTestImportedApplicants()]);

        $this->get(route('applicants.create_many.step_2'))
            ->assertSee(['Revisão de Importação', 'Importar', 'Carl Doe', 'Doug Doe'])
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    #[Test]
    public function ldiShouldntAccessStepTwoCreateApplicantsPage()
    {
        $this->actingAs(self::$userLdi);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id'))), 'importedApplicants' => $this->createTestImportedApplicants()]);

        $this->get(route('applicants.create_many.step_2'))
            ->assertStatus(403);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    #[Test]
    public function coordinatorShouldntAccessStepTwoCreateApplicantsPage()
    {
        $this->actingAs(self::$userCoord);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id'))), 'importedApplicants' => $this->createTestImportedApplicants()]);

        $this->get(route('applicants.create_many.step_2'))
            ->assertStatus(403);
    }


    // ================= Create Applicant Tests =================

    /**
     * @return array<string, string>
     */
    private function getTestAttributes(): array
    {
        $attributes = [];

        $attributes['name'] = 'Dilan Doe';
        $attributes['email'] = 'dilan@othertest.com';
        $attributes['area_code'] = '03';
        $attributes['landline'] = '01234567';
        $attributes['mobile'] = '012345678';
        $attributes['hiring_process'] = '003';

        $attributes['role_id'] = Role::factory()->createOne(
            [
                'name' => 'Super Role',
                'description' => 'Super Role',
            ]
        )->getAttribute('id');

        $attributes['course_id'] = Course::factory()->createOne(
            [
                'name' => 'Course Omicron',
                'description' => 'Course Omicron',
            ]
        )->getAttribute('id');

        $attributes['pole_id'] = Pole::factory()->createOne(
            [
                'name' => 'Pole Teta',
                'description' => 'Pole Teta',
            ]
        )->getAttribute('id');

        return $attributes;
    }

    /**
     * Guest Shouldnt create applicant
     *
     * @return void
     */
    #[Test]
    public function guestShouldntCreateApplicant()
    {
        $applicantArr = $this->getTestAttributes();

        $this->post(route('applicants.store'), $applicantArr)
            ->assertStatus(401);
    }

    /**
     * Authenticated user without permission Shouldnt create applicant
     *
     * @return void
     */
    #[Test]
    public function authenticatedUserWithoutPermissionShouldntCreateApplicant()
    {
        $applicantArr = $this->getTestAttributes();

        $this->actingAs(self::$userAlien)
            ->withSession(['loggedInUser.currentResponsibility' => null])
            ->followingRedirects()->post(route('applicants.store'), $applicantArr)
            ->assertStatus(403);
    }

    /**
     * Admin user Should create applicants
     *
     * @return void
     */
    #[Test]
    public function administratorShouldCreateApplicant()
    {
        $this->actingAs(self::$userAdm);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        $applicantArr = $this->getTestAttributes();

        $this->followingRedirects()->post(route('applicants.store'), $applicantArr)
            ->assertSee('Dilan Doe')
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    #[Test]
    public function directorShouldCreateApplicant()
    {
        $this->actingAs(self::$userDir);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        $applicantArr = $this->getTestAttributes();

        $this->followingRedirects()->post(route('applicants.store'), $applicantArr)
            ->assertSee('Dilan Doe')
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    #[Test]
    public function assistantShouldntCreateApplicant()
    {
        $this->actingAs(self::$userAss);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        $applicantArr = $this->getTestAttributes();

        $this->followingRedirects()->post(route('applicants.store'), $applicantArr)
            ->assertStatus(403);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    #[Test]
    public function secretaryShouldCreateApplicant()
    {
        $this->actingAs(self::$userSec);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        $applicantArr = $this->getTestAttributes();

        $this->followingRedirects()->post(route('applicants.store'), $applicantArr)
            ->assertSee('Dilan Doe')
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    #[Test]
    public function coordinatorShouldntCreateApplicant()
    {
        $this->actingAs(self::$userCoord);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        $applicantArr = $this->getTestAttributes();

        $this->followingRedirects()->post(route('applicants.store'), $applicantArr)
            ->assertStatus(403);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    #[Test]
    public function ldiShouldntCreateApplicant()
    {
        $this->actingAs(self::$userLdi);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        $applicantArr = $this->getTestAttributes();

        $this->followingRedirects()->post(route('applicants.store'), $applicantArr)
            ->assertStatus(403);
    }


    // ================= Create many Applicants Tests =================

    /**
     * @return array<string, array<string, string>>
     */
    private function createTestImportedApplicantsArray(): array
    {
        /** @var array<Applicant> $testApplicants */
        $testApplicants = $this->createTestImportedApplicants();

        /** @var array<string, string> $applicant0Array */
        $applicant0Array = $testApplicants[0]->toArray();
        Arr::forget($applicant0Array, ['id', 'created_at', 'updated_at']);
        $applicant0Array['check'] = 'on';

        /** @var array<string, string> $applicant1Array */
        $applicant1Array = $testApplicants[1]->toArray();
        Arr::forget($applicant1Array, ['id', 'created_at', 'updated_at']);
        $applicant1Array['check'] = 'on';

        /** @var array<string, array<string, string>> $applicantsArray */
        $applicantsArray = [];
        $applicantsArray['applicants']["0\0"] = $applicant0Array;
        $applicantsArray['applicants']["1\0"] = $applicant1Array;

        return $applicantsArray;
    }

    /** @return array<string>  */
    private function expectedApplicantInfo(): array
    {
        return ['Carl Doe', 'Doug Doe'];
    }

    /**
     * Guest Shouldnt create applicant
     *
     * @return void
     */
    #[Test]
    public function guestShouldntCreateManyApplicants()
    {
        $applicantArr = $this->createTestImportedApplicantsArray();

        $this->post(route('applicants.store_many.step_2'), $applicantArr)
            ->assertStatus(401);
    }

    /**
     * Authenticated user without permission Shouldnt create applicant
     *
     * @return void
     */
    #[Test]
    public function authenticatedUserWithoutPermissionShouldntCreateManyApplicants()
    {
        $applicantArr = $this->createTestImportedApplicantsArray();

        $this->actingAs(self::$userAlien)
            ->withSession(['loggedInUser.currentResponsibility' => null])
            ->followingRedirects()->post(route('applicants.store_many.step_2'), $applicantArr)
            ->assertStatus(403);
    }

    /**
     * Admin user Should create applicants
     *
     * @return void
     */
    #[Test]
    public function administratorShouldCreateManyApplicants()
    {
        $this->actingAs(self::$userAdm);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        $applicantArr = $this->createTestImportedApplicantsArray();

        $this->followingRedirects()->post(route('applicants.store_many.step_2'), $applicantArr)
            ->assertSee($this->expectedApplicantInfo())
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    #[Test]
    public function directorShouldCreateManyApplicants()
    {
        $this->actingAs(self::$userDir);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        $applicantArr = $this->createTestImportedApplicantsArray();

        $this->followingRedirects()->post(route('applicants.store_many.step_2'), $applicantArr)
            ->assertSee($this->expectedApplicantInfo())
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    #[Test]
    public function assistantShouldntCreateManyApplicants()
    {
        $this->actingAs(self::$userAss);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        $applicantArr = $this->createTestImportedApplicantsArray();

        $this->followingRedirects()->post(route('applicants.store_many.step_2'), $applicantArr)
            ->assertStatus(403);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    #[Test]
    public function secretaryShouldCreateManyApplicants()
    {
        $this->actingAs(self::$userSec);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        $applicantArr = $this->createTestImportedApplicantsArray();

        $this->followingRedirects()->post(route('applicants.store_many.step_2'), $applicantArr)
            ->assertSee($this->expectedApplicantInfo())
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    #[Test]
    public function coordinatorShouldntCreateManyApplicants()
    {
        $this->actingAs(self::$userCoord);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        $applicantArr = $this->createTestImportedApplicantsArray();

        $this->followingRedirects()->post(route('applicants.store_many.step_2'), $applicantArr)
            ->assertStatus(403);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    #[Test]
    public function ldiShouldntCreateManyApplicants()
    {
        $this->actingAs(self::$userLdi);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        $applicantArr = $this->createTestImportedApplicantsArray();

        $this->followingRedirects()->post(route('applicants.store_many.step_2'), $applicantArr)
            ->assertStatus(403);
    }


    // ================= Update Applicant Tests =================

    /** @return array<string, string>  */
    private function updatedApplicantData(): array
    {
        /** @var string $updatedState */
        $updatedState = CallStates::AC->name;

        return [
            'call_state' => $updatedState,
        ];
    }

    /**
     * Guest Shouldnt update applicant
     *
     * @return void
     */
    #[Test]
    public function guestShouldntUpdateApplicant()
    {
        /** @var Applicant $originalApplicant */
        $originalApplicant = Applicant::where('name', 'John Doe')->first();
        $originalApplicantArr = $originalApplicant->toArray();
        Arr::forget($originalApplicantArr, ['id', 'created_at', 'updated_at']);

        $originalApplicantArr['states'] = $this->updatedApplicantData()['call_state'];

        $this->patch(route('applicants.update', $originalApplicant->id), $originalApplicantArr)
            ->assertStatus(401);
    }

    /**
     * Authenticated user without permission Shouldnt update applicant
     *
     * @return void
     */
    #[Test]
    public function authenticatedUserWithoutPermissionShouldntUpdateApplicant()
    {
        $this->actingAs(self::$userAlien)
            ->withSession(['loggedInUser.currentResponsibility' => null]);

        /** @var Applicant $originalApplicant */
        $originalApplicant = Applicant::where('name', 'John Doe')->first();
        $originalApplicantArr = $originalApplicant->toArray();
        Arr::forget($originalApplicantArr, ['id', 'created_at', 'updated_at']);

        $originalApplicantArr['call_state'] = $this->updatedApplicantData()['call_state'];

        $this->patch(route('applicants.update', $originalApplicant->id), $originalApplicantArr)
            ->assertStatus(403);
    }

    /**
     * Admin user Should update applicant
     *
     * @return void
     */
    #[Test]
    public function administratorShouldUpdateApplicant()
    {
        $this->actingAs(self::$userAdm);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        /** @var Applicant $originalApplicant */
        $originalApplicant = Applicant::where('name', 'John Doe')->first();
        $originalApplicantArr = $originalApplicant->toArray();
        Arr::forget($originalApplicantArr, ['id', 'created_at', 'updated_at']);

        $originalApplicantArr['states'] = $this->updatedApplicantData()['call_state'];

        $this->followingRedirects()->patch(route('applicants.update', $originalApplicant->id), $originalApplicantArr)
            ->assertSee('Aceitante')
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    #[Test]
    public function directorShouldUpdateApplicant()
    {
        $this->actingAs(self::$userDir);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        /** @var Applicant $originalApplicant */
        $originalApplicant = Applicant::where('name', 'John Doe')->first();
        $originalApplicantArr = $originalApplicant->toArray();
        Arr::forget($originalApplicantArr, ['id', 'created_at', 'updated_at']);

        $originalApplicantArr['states'] = $this->updatedApplicantData()['call_state'];

        $this->followingRedirects()->patch(route('applicants.update', $originalApplicant->id), $originalApplicantArr)
            ->assertSee('Aceitante')
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    #[Test]
    public function assistantShouldntUpdateApplicant()
    {
        $this->actingAs(self::$userAss);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        /** @var Applicant $originalApplicant */
        $originalApplicant = Applicant::where('name', 'John Doe')->first();
        $originalApplicantArr = $originalApplicant->toArray();
        Arr::forget($originalApplicantArr, ['id', 'created_at', 'updated_at']);

        $originalApplicantArr['states'] = $this->updatedApplicantData()['call_state'];

        $this->followingRedirects()->patch(route('applicants.update', $originalApplicant->id), $originalApplicantArr)
            ->assertStatus(403);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    #[Test]
    public function secretaryShouldUpdateApplicant()
    {
        $this->actingAs(self::$userSec);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        /** @var Applicant $originalApplicant */
        $originalApplicant = Applicant::where('name', 'John Doe')->first();
        $originalApplicantArr = $originalApplicant->toArray();
        Arr::forget($originalApplicantArr, ['id', 'created_at', 'updated_at']);

        $originalApplicantArr['states'] = $this->updatedApplicantData()['call_state'];

        $this->followingRedirects()->patch(route('applicants.update', $originalApplicant->id), $originalApplicantArr)
            ->assertSee('Aceitante')
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    #[Test]
    public function coordinatorShouldntUpdateApplicant()
    {
        $this->actingAs(self::$userCoord);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        /** @var Applicant $originalApplicant */
        $originalApplicant = Applicant::where('name', 'John Doe')->first();
        $originalApplicantArr = $originalApplicant->toArray();
        Arr::forget($originalApplicantArr, ['id', 'created_at', 'updated_at']);

        $originalApplicantArr['states'] = $this->updatedApplicantData()['call_state'];

        $this->patch(route('applicants.update', $originalApplicant->id), $originalApplicantArr)
            ->assertStatus(403);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    #[Test]
    public function ldiShouldntUpdateApplicant()
    {
        $this->actingAs(self::$userLdi);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        /** @var Applicant $originalApplicant */
        $originalApplicant = Applicant::where('name', 'John Doe')->first();
        $originalApplicantArr = $originalApplicant->toArray();
        Arr::forget($originalApplicantArr, ['id', 'created_at', 'updated_at']);

        $originalApplicantArr['states'] = $this->updatedApplicantData()['call_state'];

        $this->patch(route('applicants.update', $originalApplicant->id), $originalApplicantArr)
            ->assertStatus(403);
    }


    // ================= Delete Applicant Tests =================

    /**
     * Guest Shouldnt delete applicant
     *
     * @return void
     */
    #[Test]
    public function guestShouldntDeleteApplicants()
    {
        $applicantBefore = Applicant::find(1);

        $this->delete(route('applicants.destroy', 1))
            ->assertStatus(401);

        $applicantAfter = Applicant::find(1);
        $this->assertEquals($applicantBefore, $applicantAfter);
    }

    /**
     * Authenticated user without permission Shouldnt delete applicant
     *
     * @return void
     */
    #[Test]
    public function authenticatedUserWithoutPermissionShouldntDeleteApplicants()
    {
        $this->actingAs(self::$userAlien)
            ->withSession(['loggedInUser.currentResponsibility' => null]);

        $applicantBefore = Applicant::find(1);

        $this->delete(route('applicants.destroy', 1))
            ->assertStatus(403);

        $applicantAfter = Applicant::find(1);
        $this->assertEquals($applicantBefore, $applicantAfter);
    }

    /**
     * Admin user Should delete applicant
     *
     * @return void
     */
    #[Test]
    public function administratorShouldDeleteApplicants()
    {
        $this->actingAs(self::$userAdm);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        $applicantBefore = Applicant::find(1);

        $this->followingRedirects()->delete(route('applicants.destroy', 1))
            ->assertStatus(200);

        $applicantAfter = Applicant::find(1);

        $this->assertNotNull($applicantBefore);
        $this->assertNull($applicantAfter);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    #[Test]
    public function directorShouldDeleteApplicants()
    {
        $this->actingAs(self::$userDir);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        $applicantBefore = Applicant::find(1);

        $this->followingRedirects()->delete(route('applicants.destroy', 1))
            ->assertStatus(200);

        $applicantAfter = Applicant::find(1);

        $this->assertNotNull($applicantBefore);
        $this->assertNull($applicantAfter);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    #[Test]
    public function assistantShouldntDeleteApplicants()
    {
        $this->actingAs(self::$userAss);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        $applicantBefore = Applicant::find(1);

        $this->delete(route('applicants.destroy', 1))
            ->assertStatus(403);

        $applicantAfter = Applicant::find(1);
        $this->assertEquals($applicantBefore, $applicantAfter);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    #[Test]
    public function secretaryShouldDeleteApplicants()
    {
        $this->actingAs(self::$userSec);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        $applicantBefore = Applicant::find(1);

        $this->followingRedirects()->delete(route('applicants.destroy', 1))
            ->assertStatus(200);

        $applicantAfter = Applicant::find(1);

        $this->assertNotNull($applicantBefore);
        $this->assertNull($applicantAfter);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    #[Test]
    public function ldiShouldntDeleteApplicants()
    {
        $this->actingAs(self::$userLdi);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        $applicantBefore = Applicant::find(1);

        $this->delete(route('applicants.destroy', 1))
            ->assertStatus(403);

        $applicantAfter = Applicant::find(1);
        $this->assertEquals($applicantBefore, $applicantAfter);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    #[Test]
    public function coordinatorShouldntDeleteApplicants()
    {
        $this->actingAs(self::$userCoord);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        $applicantBefore = Applicant::find(1);

        $this->delete(route('applicants.destroy', 1))
            ->assertStatus(403);

        $applicantAfter = Applicant::find(1);
        $this->assertEquals($applicantBefore, $applicantAfter);
    }
}
