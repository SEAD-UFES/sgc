<?php

namespace Tests\Feature;

use App\Models\Approved;
use App\Models\ApprovedState;
use App\Models\Course;
use App\Models\Pole;
use App\Models\Role;
use App\Models\User;
use App\Models\UserType;
use App\Models\UserTypeAssignment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Tests\TestCase;

class ApprovedTest extends TestCase
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

        Approved::factory()->createOne(
            [
                'name' => 'John Doe',
                'email' => 'john@test.com',
                'area_code' => '01',
                'phone' => '12345678',
                'mobile' => '123456789',
                'announcement' => '001',
            ]
        );

        Approved::factory()->createOne(
            [
                'name' => 'Jane Doe',
                'email' => 'jane@othertest.com',
                'area_code' => '02',
                'phone' => '01234567',
                'mobile' => '012345678',
                'announcement' => '002',
            ]
        );

        ApprovedState::factory()->createOne([
            'name' => 'Não contatado',
        ]);

        ApprovedState::factory()->createOne([
            'name' => 'Updated State',
        ]);
    }


    // ================= See Approveds list Tests =================

    /**
     * Guest Shouldnt list approveds.
     *
     * @return void
     *
     * @test
     */
    public function guestShouldntListApproveds()
    {
        $this->get(route('approveds.index'))
            ->assertRedirect(route('auth.login'));
    }

    /**
     * Authenticated user without permission Shouldnt list approveds
     *
     * @return void
     *
     * @test
     */
    public function authenticatedUserWithoutPermissionShouldntListApproveds()
    {
        $this->actingAs(self::$userAlien)
            ->withSession(['loggedInUser.currentUta' => null]);

        $this->get(route('approveds.index'))
            ->assertStatus(403);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     *
     * @test
     */
    public function administratorShouldListApproveds()
    {
        $this->actingAs(self::$userAdm);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentUta' => $authUser->getFirstUta()]);

        $this->get(route('approveds.index'))
            ->assertSee(['John Doe', 'Jane Doe', 'john@test.com', 'jane@othertest.com'])
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     *
     * @test
     */
    public function directorShouldListApproveds()
    {
        $this->actingAs(self::$userDir);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentUta' => $authUser->getFirstUta()]);

        $this->get(route('approveds.index'))
            ->assertSee(['John Doe', 'Jane Doe', 'john@test.com', 'jane@othertest.com'])
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     *
     * @test
     */
    public function assistantShouldntListApproveds()
    {
        $this->actingAs(self::$userAss);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentUta' => $authUser->getFirstUta()]);

        $this->get(route('approveds.index'))
            ->assertStatus(403);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     *
     * @test
     */
    public function secretaryShouldListApproveds()
    {
        $this->actingAs(self::$userSec);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentUta' => $authUser->getFirstUta()]);

        $this->get(route('approveds.index'))
            ->assertSee(['John Doe', 'Jane Doe', 'john@test.com', 'jane@othertest.com'])
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     *
     * @test
     */
    public function ldiShouldntListApproveds()
    {
        $this->actingAs(self::$userLdi);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentUta' => $authUser->getFirstUta()]);

        $this->get(route('approveds.index'))
            ->assertStatus(403);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     *
     * @test
     */
    public function coordinatorShouldListApproveds()
    {
        $this->actingAs(self::$userCoord);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentUta' => $authUser->getFirstUta()]);

        $this->get(route('approveds.index'))
            ->assertSee(['John Doe', 'Jane Doe', 'john@test.com', 'jane@othertest.com'])
            ->assertStatus(200);
    }


    // ================= See Create Form Tests =================


    /**
     * Guest Shouldnt access create approved page
     *
     * @return void
     *
     * @test
     */
    public function guestShouldntAccessCreateApprovedPage()
    {
        $this->get(route('approveds.create'))
            ->assertRedirect(route('auth.login'));
    }

    /**
     * Authenticated user without permission Shouldnt Access create approved page
     *
     * @return void
     *
     * @test
     */
    public function authenticatedUserWithoutPermissionShouldntAccessCreateApprovedPage()
    {
        $this->actingAs(self::$userAlien)
            ->withSession(['loggedInUser.currentUta' => null]);

        $this->get(route('approveds.create'))
            ->assertStatus(403);
    }

    /**
     * Admin user Should access create approved page
     *
     * @return void
     *
     * @test
     */
    public function administratorShouldAccessCreateApprovedPage()
    {
        $this->actingAs(self::$userAdm);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentUta' => $authUser->getFirstUta()]);

        $this->get(route('approveds.create'))
            ->assertSee(['Cadastrar Aprovado', 'Telefone'])
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     *
     * @test
     */
    public function directorShouldAccessCreateApprovedPage()
    {
        $this->actingAs(self::$userDir);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentUta' => $authUser->getFirstUta()]);

        $this->get(route('approveds.create'))
            ->assertSee(['Cadastrar Aprovado', 'Telefone'])
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     *
     * @test
     */
    public function assistantShouldntAccessCreateApprovedPage()
    {
        $this->actingAs(self::$userAss);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentUta' => $authUser->getFirstUta()]);

        $this->get(route('approveds.create'))
            ->assertStatus(403);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     *
     * @test
     */
    public function secretaryShouldAccessCreateApprovedPage()
    {
        $this->actingAs(self::$userSec);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentUta' => $authUser->getFirstUta()]);

        $this->get(route('approveds.create'))
            ->assertSee(['Cadastrar Aprovado', 'Telefone'])
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     *
     * @test
     */
    public function ldiShouldntAccessCreateApprovedPage()
    {
        $this->actingAs(self::$userLdi);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentUta' => $authUser->getFirstUta()]);

        $this->get(route('approveds.create'))
            ->assertStatus(403);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     *
     * @test
     */
    public function coordinatorShouldntAccessCreateApprovedPage()
    {
        $this->actingAs(self::$userCoord);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentUta' => $authUser->getFirstUta()]);

        $this->get(route('approveds.create'))
            ->assertStatus(403);
    }


    // ================= See Create Step 1 Form Tests =================

    /**
     * Guest Shouldnt access create approved page
     *
     * @return void
     *
     * @test
     */
    public function guestShouldntAccessStepOneCreateApprovedsPage()
    {
        $this->get(route('approveds.createMany.step1'))
            ->assertRedirect(route('auth.login'));
    }

    /**
     * Authenticated user without permission Shouldnt Access create approved page
     *
     * @return void
     *
     * @test
     */
    public function authenticatedUserWithoutPermissionShouldntAccessStepOneCreateApprovedsPage()
    {
        $this->actingAs(self::$userAlien)
            ->withSession(['loggedInUser.currentUta' => null]);

        $this->get(route('approveds.createMany.step1'))
            ->assertStatus(403);
    }

    /**
     * Admin user Should access create approved page
     *
     * @return void
     *
     * @test
     */
    public function administratorShouldAccessStepOneCreateApprovedsPage()
    {
        $this->actingAs(self::$userAdm);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentUta' => $authUser->getFirstUta()]);

        $this->get(route('approveds.createMany.step1'))
            ->assertSee(['Importar Aprovados', 'Enviar arquivo'])
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     *
     * @test
     */
    public function directorShouldAccessStepOneCreateApprovedsPage()
    {
        $this->actingAs(self::$userDir);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentUta' => $authUser->getFirstUta()]);

        $this->get(route('approveds.createMany.step1'))
            ->assertSee(['Importar Aprovados', 'Enviar arquivo'])
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     *
     * @test
     */
    public function assistantShouldntAccessStepOneCreateApprovedsPage()
    {
        $this->actingAs(self::$userAss);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentUta' => $authUser->getFirstUta()]);

        $this->get(route('approveds.createMany.step1'))
            ->assertStatus(403);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     *
     * @test
     */
    public function secretaryShouldAccessStepOneCreateApprovedsPage()
    {
        $this->actingAs(self::$userSec);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentUta' => $authUser->getFirstUta()]);

        $this->get(route('approveds.createMany.step1'))
            ->assertSee(['Importar Aprovados', 'Enviar arquivo'])
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     *
     * @test
     */
    public function ldiShouldntAccessStepOneCreateApprovedsPage()
    {
        $this->actingAs(self::$userLdi);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentUta' => $authUser->getFirstUta()]);

        $this->get(route('approveds.createMany.step1'))
            ->assertStatus(403);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     *
     * @test
     */
    public function coordinatorShouldntAccessStepOneCreateApprovedsPage()
    {
        $this->actingAs(self::$userCoord);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentUta' => $authUser->getFirstUta()]);

        $this->get(route('approveds.createMany.step1'))
            ->assertStatus(403);
    }


    // ================= See Create Step 2 Form Tests =================

    /**
     * @return array<Approved>
     */
    private function createTestImportedApproveds(): array
    {
        /** @var array<Approved> $importedApproveds */
        $importedApproveds = [];

        /** @var Approved $newApproved1 $ */
        $newApproved1 = Approved::factory()->makeOne(
            [
                'name' => 'Carl Doe',
                'email' => (debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2)[1]['function']) . '_1@test-case.com',
            ]
        );

        /** @var Approved $newApproved2 $ */
        $newApproved2 = Approved::factory()->makeOne(
            [
                'name' => 'Doug Doe',
                'email' => (debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2)[1]['function']) . '_2@test-case.com',
            ]
        );

        array_push($importedApproveds, $newApproved1, $newApproved2);

        return $importedApproveds;
    }

    /**
     * Guest Shouldnt access create approved page
     *
     * @return void
     *
     * @test
     */
    public function guestShouldntAccessStepTwoCreateApprovedsPage()
    {
        $this->get(route('approveds.createMany.step2'))
            ->assertRedirect(route('auth.login'));
    }

    /**
     * Authenticated user without permission Shouldnt Access create approved page
     *
     * @return void
     *
     * @test
     */
    public function authenticatedUserWithoutPermissionShouldntAccessStepTwoCreateApprovedsPage()
    {
        $this->actingAs(self::$userAlien)
            ->withSession(['loggedInUser.currentUta' => null, 'importedApproveds' => $this->createTestImportedApproveds()]);

        $this->get(route('approveds.createMany.step2'))
            ->assertStatus(403);
    }

    /**
     * Admin user Should access create approved page
     *
     * @return void
     *
     * @test
     */
    public function administratorShouldAccessStepTwoCreateApprovedsPage()
    {
        $this->actingAs(self::$userAdm);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentUta' => $authUser->getFirstUta(), 'importedApproveds' => $this->createTestImportedApproveds()]);

        $this->get(route('approveds.createMany.step2'))
            ->assertSee(['Revisão de Importação', 'Importar', 'Carl Doe', 'Doug Doe'])
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     *
     * @test
     */
    public function directorShouldAccessStepTwoCreateApprovedsPage()
    {
        $this->actingAs(self::$userDir);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentUta' => $authUser->getFirstUta(), 'importedApproveds' => $this->createTestImportedApproveds()]);

        $this->get(route('approveds.createMany.step2'))
            ->assertSee(['Revisão de Importação', 'Importar', 'Carl Doe', 'Doug Doe'])
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     *
     * @test
     */
    public function assistantShouldntAccessStepTwoCreateApprovedsPage()
    {
        $this->actingAs(self::$userAss);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentUta' => $authUser->getFirstUta(), 'importedApproveds' => $this->createTestImportedApproveds()]);

        $this->get(route('approveds.createMany.step2'))
            ->assertStatus(403);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     *
     * @test
     */
    public function secretaryShouldAccessStepTwoCreateApprovedsPage()
    {
        $this->actingAs(self::$userSec);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentUta' => $authUser->getFirstUta(), 'importedApproveds' => $this->createTestImportedApproveds()]);

        $this->get(route('approveds.createMany.step2'))
            ->assertSee(['Revisão de Importação', 'Importar', 'Carl Doe', 'Doug Doe'])
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     *
     * @test
     */
    public function ldiShouldntAccessStepTwoCreateApprovedsPage()
    {
        $this->actingAs(self::$userLdi);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentUta' => $authUser->getFirstUta(), 'importedApproveds' => $this->createTestImportedApproveds()]);

        $this->get(route('approveds.createMany.step2'))
            ->assertStatus(403);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     *
     * @test
     */
    public function coordinatorShouldntAccessStepTwoCreateApprovedsPage()
    {
        $this->actingAs(self::$userCoord);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentUta' => $authUser->getFirstUta(), 'importedApproveds' => $this->createTestImportedApproveds()]);

        $this->get(route('approveds.createMany.step2'))
            ->assertStatus(403);
    }


    // ================= Create Approved Tests =================

    /**
     * @return array<string, string>
     */
    private function getTestAttributes(): array
    {
        //setting up scenario
        ApprovedState::factory()->create(
            [
                'name' => 'Não contatado',
                'description' => 'Bar',
            ]
        );

        $attributes = [];

        $attributes['name'] = 'Dilan Doe';
        $attributes['email'] = 'dilan@othertest.com';
        $attributes['area_code'] = '03';
        $attributes['phone'] = '01234567';
        $attributes['mobile'] = '012345678';
        $attributes['announcement'] = '003';

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
     * Guest Shouldnt create approved
     *
     * @return void
     *
     * @test
     */
    public function guestShouldntCreateApproved()
    {
        $approvedArr = $this->getTestAttributes();

        $this->post(route('approveds.store'), $approvedArr)
            ->assertRedirect(route('auth.login'));
    }

    /**
     * Authenticated user without permission Shouldnt create approved
     *
     * @return void
     *
     * @test
     */
    public function authenticatedUserWithoutPermissionShouldntCreateApproved()
    {
        $approvedArr = $this->getTestAttributes();

        $this->actingAs(self::$userAlien)
            ->withSession(['loggedInUser.currentUta' => null])
            ->followingRedirects()->post(route('approveds.store'), $approvedArr)
            ->assertStatus(403);
    }

    /**
     * Admin user Should create approveds
     *
     * @return void
     *
     * @test
     */
    public function administratorShouldCreateApproved()
    {
        $this->actingAs(self::$userAdm);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentUta' => $authUser->getFirstUta()]);

        $approvedArr = $this->getTestAttributes();

        $this->followingRedirects()->post(route('approveds.store'), $approvedArr)
            ->assertSee('Dilan Doe')
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     *
     * @test
     */
    public function directorShouldCreateApproved()
    {
        $this->actingAs(self::$userDir);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentUta' => $authUser->getFirstUta()]);

        $approvedArr = $this->getTestAttributes();

        $this->followingRedirects()->post(route('approveds.store'), $approvedArr)
            ->assertSee('Dilan Doe')
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     *
     * @test
     */
    public function assistantShouldntCreateApproved()
    {
        $this->actingAs(self::$userAss);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentUta' => $authUser->getFirstUta()]);

        $approvedArr = $this->getTestAttributes();

        $this->followingRedirects()->post(route('approveds.store'), $approvedArr)
            ->assertStatus(403);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     *
     * @test
     */
    public function secretaryShouldCreateApproved()
    {
        $this->actingAs(self::$userSec);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentUta' => $authUser->getFirstUta()]);

        $approvedArr = $this->getTestAttributes();

        $this->followingRedirects()->post(route('approveds.store'), $approvedArr)
            ->assertSee('Dilan Doe')
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     *
     * @test
     */
    public function coordinatorShouldntCreateApproved()
    {
        $this->actingAs(self::$userCoord);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentUta' => $authUser->getFirstUta()]);

        $approvedArr = $this->getTestAttributes();

        $this->followingRedirects()->post(route('approveds.store'), $approvedArr)
            ->assertStatus(403);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     *
     * @test
     */
    public function ldiShouldntCreateApproved()
    {
        $this->actingAs(self::$userLdi);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentUta' => $authUser->getFirstUta()]);

        $approvedArr = $this->getTestAttributes();

        $this->followingRedirects()->post(route('approveds.store'), $approvedArr)
            ->assertStatus(403);
    }


    // ================= Create many Approveds Tests =================

    /**
     * @return array<string, array<string, string>>
     */
    private function createTestImportedApprovedsArray(): array
    {
        /** @var array<Approved> $testApproveds */
        $testApproveds = $this->createTestImportedApproveds();

        /** @var array<string, string> $approved0Array */
        $approved0Array = $testApproveds[0]->toArray();
        Arr::forget($approved0Array, ['id', 'created_at', 'updated_at']);
        $approved0Array['check'] = 'on';

        /** @var array<string, string> $approved1Array */
        $approved1Array = $testApproveds[1]->toArray();
        Arr::forget($approved1Array, ['id', 'created_at', 'updated_at']);
        $approved1Array['check'] = 'on';

        /** @var array<string, array<string, string>> $approvedsArray */
        $approvedsArray = [];
        $approvedsArray['approveds']["0\0"] = $approved0Array;
        $approvedsArray['approveds']["1\0"] = $approved1Array;

        return $approvedsArray;
    }

    /** @return array<string>  */
    private function expectedApprovedInfo(): array
    {
        return ['Carl Doe', 'Doug Doe'];
    }

    /**
     * Guest Shouldnt create approved
     *
     * @return void
     *
     * @test
     */
    public function guestShouldntCreateManyApproveds()
    {
        $approvedArr = $this->createTestImportedApprovedsArray();

        $this->post(route('approveds.storeMany.step2'), $approvedArr)
            ->assertRedirect(route('auth.login'));
    }

    /**
     * Authenticated user without permission Shouldnt create approved
     *
     * @return void
     *
     * @test
     */
    public function authenticatedUserWithoutPermissionShouldntCreateManyApproveds()
    {
        $approvedArr = $this->createTestImportedApprovedsArray();

        $this->actingAs(self::$userAlien)
            ->withSession(['loggedInUser.currentUta' => null])
            ->followingRedirects()->post(route('approveds.storeMany.step2'), $approvedArr)
            ->assertStatus(403);
    }

    /**
     * Admin user Should create approveds
     *
     * @return void
     *
     * @test
     */
    public function administratorShouldCreateManyApproveds()
    {
        $this->actingAs(self::$userAdm);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentUta' => $authUser->getFirstUta()]);

        $approvedArr = $this->createTestImportedApprovedsArray();

        $this->followingRedirects()->post(route('approveds.storeMany.step2'), $approvedArr)
            ->assertSee($this->expectedApprovedInfo())
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     *
     * @test
     */
    public function directorShouldCreateManyApproveds()
    {
        $this->actingAs(self::$userDir);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentUta' => $authUser->getFirstUta()]);

        $approvedArr = $this->createTestImportedApprovedsArray();

        $this->followingRedirects()->post(route('approveds.storeMany.step2'), $approvedArr)
            ->assertSee($this->expectedApprovedInfo())
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     *
     * @test
     */
    public function assistantShouldntCreateManyApproveds()
    {
        $this->actingAs(self::$userAss);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentUta' => $authUser->getFirstUta()]);

        $approvedArr = $this->createTestImportedApprovedsArray();

        $this->followingRedirects()->post(route('approveds.storeMany.step2'), $approvedArr)
            ->assertStatus(403);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     *
     * @test
     */
    public function secretaryShouldCreateManyApproveds()
    {
        $this->actingAs(self::$userSec);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentUta' => $authUser->getFirstUta()]);

        $approvedArr = $this->createTestImportedApprovedsArray();

        $this->followingRedirects()->post(route('approveds.storeMany.step2'), $approvedArr)
            ->assertSee($this->expectedApprovedInfo())
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     *
     * @test
     */
    public function coordinatorShouldntCreateManyApproveds()
    {
        $this->actingAs(self::$userCoord);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentUta' => $authUser->getFirstUta()]);

        $approvedArr = $this->createTestImportedApprovedsArray();

        $this->followingRedirects()->post(route('approveds.storeMany.step2'), $approvedArr)
            ->assertStatus(403);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     *
     * @test
     */
    public function ldiShouldntCreateManyApproveds()
    {
        $this->actingAs(self::$userLdi);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentUta' => $authUser->getFirstUta()]);

        $approvedArr = $this->createTestImportedApprovedsArray();

        $this->followingRedirects()->post(route('approveds.storeMany.step2'), $approvedArr)
            ->assertStatus(403);
    }


    // ================= Update Approved Tests =================

    /** @return array<string>  */
    private function updatedApprovedData(): array
    {
        /** @var ApprovedState $updatedState */
        $updatedState = ApprovedState::where('name', 'Updated State')->first();

        return [
            'states' => (string) $updatedState->id,
        ];
    }

    /**
     * Guest Shouldnt update approved
     *
     * @return void
     *
     * @test
     */
    public function guestShouldntUpdateApproved()
    {
        /** @var Approved $originalApproved */
        $originalApproved = Approved::where('name', 'John Doe')->first();
        $originalApprovedArr = $originalApproved->toArray();
        Arr::forget($originalApprovedArr, ['id', 'created_at', 'updated_at']);

        $originalApprovedArr['states'] = $this->updatedApprovedData()['states'];

        $this->patch(route('approveds.update', $originalApproved->id), $originalApprovedArr)
            ->assertRedirect(route('auth.login'));
    }

    /**
     * Authenticated user without permission Shouldnt update approved
     *
     * @return void
     *
     * @test
     */
    public function authenticatedUserWithoutPermissionShouldntUpdateApproved()
    {
        $this->actingAs(self::$userAlien)
            ->withSession(['loggedInUser.currentUta' => null]);

        /** @var Approved $originalApproved */
        $originalApproved = Approved::where('name', 'John Doe')->first();
        $originalApprovedArr = $originalApproved->toArray();
        Arr::forget($originalApprovedArr, ['id', 'created_at', 'updated_at']);

        $originalApprovedArr['states'] = $this->updatedApprovedData()['states'];

        $this->patch(route('approveds.update', $originalApproved->id), $originalApprovedArr)
            ->assertStatus(403);
    }

    /**
     * Admin user Should update approved
     *
     * @return void
     *
     * @test
     */
    public function administratorShouldUpdateApproved()
    {
        $this->actingAs(self::$userAdm);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentUta' => $authUser->getFirstUta()]);

        /** @var Approved $originalApproved */
        $originalApproved = Approved::where('name', 'John Doe')->first();
        $originalApprovedArr = $originalApproved->toArray();
        Arr::forget($originalApprovedArr, ['id', 'created_at', 'updated_at']);

        $originalApprovedArr['states'] = $this->updatedApprovedData()['states'];

        $this->followingRedirects()->patch(route('approveds.update', $originalApproved->id), $originalApprovedArr)
            ->assertSee('Updated State')
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     *
     * @test
     */
    public function directorShouldUpdateApproved()
    {
        $this->actingAs(self::$userDir);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentUta' => $authUser->getFirstUta()]);

        /** @var Approved $originalApproved */
        $originalApproved = Approved::where('name', 'John Doe')->first();
        $originalApprovedArr = $originalApproved->toArray();
        Arr::forget($originalApprovedArr, ['id', 'created_at', 'updated_at']);

        $originalApprovedArr['states'] = $this->updatedApprovedData()['states'];

        $this->followingRedirects()->patch(route('approveds.update', $originalApproved->id), $originalApprovedArr)
            ->assertSee('Updated State')
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     *
     * @test
     */
    public function assistantShouldntUpdateApproved()
    {
        $this->actingAs(self::$userAss);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentUta' => $authUser->getFirstUta()]);

        /** @var Approved $originalApproved */
        $originalApproved = Approved::where('name', 'John Doe')->first();
        $originalApprovedArr = $originalApproved->toArray();
        Arr::forget($originalApprovedArr, ['id', 'created_at', 'updated_at']);

        $originalApprovedArr['states'] = $this->updatedApprovedData()['states'];

        $this->followingRedirects()->patch(route('approveds.update', $originalApproved->id), $originalApprovedArr)
            ->assertStatus(403);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     *
     * @test
     */
    public function secretaryShouldUpdateApproved()
    {
        $this->actingAs(self::$userSec);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentUta' => $authUser->getFirstUta()]);

        /** @var Approved $originalApproved */
        $originalApproved = Approved::where('name', 'John Doe')->first();
        $originalApprovedArr = $originalApproved->toArray();
        Arr::forget($originalApprovedArr, ['id', 'created_at', 'updated_at']);

        $originalApprovedArr['states'] = $this->updatedApprovedData()['states'];

        $this->followingRedirects()->patch(route('approveds.update', $originalApproved->id), $originalApprovedArr)
            ->assertSee('Updated State')
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     *
     * @test
     */
    public function coordinatorShouldntUpdateApproved()
    {
        $this->actingAs(self::$userCoord);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentUta' => $authUser->getFirstUta()]);

        /** @var Approved $originalApproved */
        $originalApproved = Approved::where('name', 'John Doe')->first();
        $originalApprovedArr = $originalApproved->toArray();
        Arr::forget($originalApprovedArr, ['id', 'created_at', 'updated_at']);

        $originalApprovedArr['states'] = $this->updatedApprovedData()['states'];

        $this->patch(route('approveds.update', $originalApproved->id), $originalApprovedArr)
            ->assertStatus(403);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     *
     * @test
     */
    public function ldiShouldntUpdateApproved()
    {
        $this->actingAs(self::$userLdi);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentUta' => $authUser->getFirstUta()]);

        /** @var Approved $originalApproved */
        $originalApproved = Approved::where('name', 'John Doe')->first();
        $originalApprovedArr = $originalApproved->toArray();
        Arr::forget($originalApprovedArr, ['id', 'created_at', 'updated_at']);

        $originalApprovedArr['states'] = $this->updatedApprovedData()['states'];

        $this->patch(route('approveds.update', $originalApproved->id), $originalApprovedArr)
            ->assertStatus(403);
    }


    // ================= Delete Approved Tests =================

    /**
     * Guest Shouldnt delete approved
     *
     * @return void
     *
     * @test
     */
    public function guestShouldntDeleteApproveds()
    {
        $approvedBefore = Approved::find(1);

        $this->delete(route('approveds.destroy', 1))
            ->assertRedirect(route('auth.login'));

        $approvedAfter = Approved::find(1);
        $this->assertEquals($approvedBefore, $approvedAfter);
    }

    /**
     * Authenticated user without permission Shouldnt delete approved
     *
     * @return void
     *
     * @test
     */
    public function authenticatedUserWithoutPermissionShouldntDeleteApproveds()
    {
        $this->actingAs(self::$userAlien)
            ->withSession(['loggedInUser.currentUta' => null]);

        $approvedBefore = Approved::find(1);

        $this->delete(route('approveds.destroy', 1))
            ->assertStatus(403);

        $approvedAfter = Approved::find(1);
        $this->assertEquals($approvedBefore, $approvedAfter);
    }

    /**
     * Admin user Should delete approved
     *
     * @return void
     *
     * @test
     */
    public function administratorShouldDeleteApproveds()
    {
        $this->actingAs(self::$userAdm);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentUta' => $authUser->getFirstUta()]);

        $approvedBefore = Approved::find(1);

        $this->followingRedirects()->delete(route('approveds.destroy', 1))
            ->assertStatus(200);

        $approvedAfter = Approved::find(1);

        $this->assertNotNull($approvedBefore);
        $this->assertNull($approvedAfter);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     *
     * @test
     */
    public function directorShouldDeleteApproveds()
    {
        $this->actingAs(self::$userDir);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentUta' => $authUser->getFirstUta()]);

        $approvedBefore = Approved::find(1);

        $this->followingRedirects()->delete(route('approveds.destroy', 1))
            ->assertStatus(200);

        $approvedAfter = Approved::find(1);

        $this->assertNotNull($approvedBefore);
        $this->assertNull($approvedAfter);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     *
     * @test
     */
    public function assistantShouldntDeleteApproveds()
    {
        $this->actingAs(self::$userAss);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentUta' => $authUser->getFirstUta()]);

        $approvedBefore = Approved::find(1);

        $this->delete(route('approveds.destroy', 1))
            ->assertStatus(403);

        $approvedAfter = Approved::find(1);
        $this->assertEquals($approvedBefore, $approvedAfter);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     *
     * @test
     */
    public function secretaryShouldDeleteApproveds()
    {
        $this->actingAs(self::$userSec);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentUta' => $authUser->getFirstUta()]);

        $approvedBefore = Approved::find(1);

        $this->followingRedirects()->delete(route('approveds.destroy', 1))
            ->assertStatus(200);

        $approvedAfter = Approved::find(1);

        $this->assertNotNull($approvedBefore);
        $this->assertNull($approvedAfter);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     *
     * @test
     */
    public function ldiShouldntDeleteApproveds()
    {
        $this->actingAs(self::$userLdi);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentUta' => $authUser->getFirstUta()]);

        $approvedBefore = Approved::find(1);

        $this->delete(route('approveds.destroy', 1))
            ->assertStatus(403);

        $approvedAfter = Approved::find(1);
        $this->assertEquals($approvedBefore, $approvedAfter);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     *
     * @test
     */
    public function coordinatorShouldntDeleteApproveds()
    {
        $this->actingAs(self::$userCoord);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentUta' => $authUser->getFirstUta()]);

        $approvedBefore = Approved::find(1);

        $this->delete(route('approveds.destroy', 1))
            ->assertStatus(403);

        $approvedAfter = Approved::find(1);
        $this->assertEquals($approvedBefore, $approvedAfter);
    }
}
