<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use App\Enums\MaritalStatuses;
use App\Enums\States;
use App\Models\BankAccount;
use App\Models\Course;
use App\Models\DocumentType;
use App\Models\Employee;
use App\Models\User;
use App\Models\UserType;
use App\Models\Responsibility;
use App\Repositories\ResponsibilityRepository;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Tests\TestCase;

/**
 * @mixin \App\Models\User
 */
final class EmployeeTest extends TestCase
{
    use DatabaseMigrations;
    use WithFaker;

    private static User $userAdm;
    private static User $userDir;
    private static User $userAss;
    private static User $userSec;
    private static User $userCoord;
    private static User $userLdi;
    private static User $userAlien;

    private ResponsibilityRepository $responsibilityRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->responsibilityRepository = new ResponsibilityRepository();

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

        BankAccount::factory()->createOne([
            'bank_name' => 'Banco do Brasil',
            'agency_number' => '123',
            'account' => '12345678',
            'employee_id' => Employee::factory()->createOne(
                [
                    'name' => 'John Doe',
                ]
            ),
        ]);

        BankAccount::factory()->createOne([
            'bank_name' => 'Banco do Brasil',
            'agency_number' => '123',
            'account' => '12345678',
            'employee_id' => Employee::factory()->createOne(
                [
                    'name' => 'Jane Doe',
                ]
            ),
        ]);

        DocumentType::factory()->createOne([
            'name' => 'RG',
        ]);
        //$this->createApplication();
    }

    // ================= See Employees list Tests =================

    /**
     * Guest Shouldnt list employees
     *
     * @return void
     */
    #[Test]
    public function guestShouldntListEmployees(): void
    {
        $this->get(route('employees.index'))
            ->assertStatus(401);
    }

    /**
     * Authenticated user without permission Shouldnt list employees
     *
     * @return void
     */
    #[Test]
    public function authenticatedUserWithoutPermissionShouldntListEmployees(): void
    {
        $this->actingAs(self::$userAlien)
            ->withSession(['loggedInUser.currentResponsibility' => null]);

        $this->get(route('employees.index'))
            ->assertStatus(403);
    }

    /**
     * Admin user Should list employees
     *
     * @return void
     */
    #[Test]
    public function administratorShouldListEmployees(): void
    {
        $this->actingAs(self::$userAdm);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        $this->get(route('employees.index'))
            ->assertSee('Listar Colaboradores')
            ->assertSee(['John Doe', 'Jane Doe'])
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    #[Test]
    public function directorShouldListEmployees(): void
    {
        $this->actingAs(self::$userDir);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        $this->get(route('employees.index'))
            ->assertSee(['John Doe', 'Jane Doe'])
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    #[Test]
    public function assistantShouldListEmployees(): void
    {
        $this->actingAs(self::$userAss);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        $this->get(route('employees.index'))
            ->assertSee(['John Doe', 'Jane Doe'])
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    #[Test]
    public function secretaryShouldListEmployees(): void
    {
        $this->actingAs(self::$userSec);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        $this->get(route('employees.index'))
            ->assertSee(['John Doe', 'Jane Doe'])
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    #[Test]
    public function ldiShouldntListEmployees(): void
    {
        $this->actingAs(self::$userLdi);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        $this->get(route('employees.index'))
            ->assertStatus(403);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    #[Test]
    public function coordinatorShouldListEmployees(): void
    {
        $this->actingAs(self::$userCoord);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        $this->get(route('employees.index'))
            ->assertSee(['John Doe', 'Jane Doe'])
            ->assertStatus(200);
    }

    // ================= See Employee details Tests =================

    /**
     * Guest Shouldnt access employee details page
     *
     * @return void
     */
    #[Test]
    public function guestShouldntAccessEmployeesDetailsPage(): void
    {
        $this->get(route('employees.show', 1))
            ->assertStatus(401);
    }

    /**
     * Authenticated user without permission Shouldnt Access Employees Details Page
     *
     * @return void
     */
    #[Test]
    public function authenticatedUserWithoutPermissionShouldntAccessEmployeesDetailsPage(): void
    {
        $this->actingAs(self::$userAlien)
            ->withSession(['loggedInUser.currentResponsibility' => null]);

        $this->get(route('employees.show', 1))
            ->assertStatus(403);
    }

    /**
     * Admin user Should access employee details page
     *
     * @return void
     */
    #[Test]
    public function administratorShouldAccessEmployeesDetailsPage(): void
    {
        $this->actingAs(self::$userAdm);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        $this->get(route('employees.show', 1))
            ->assertSee(['Listar Colaboradores', 'Nome:', 'CPF:', 'Profissão:', 'Contato e Endereço'])
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    #[Test]
    public function directorShouldAccessEmployeesDetailsPage(): void
    {
        $this->actingAs(self::$userDir);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        $this->get(route('employees.show', 1))
            ->assertSee(['Listar Colaboradores', 'Nome:', 'CPF:', 'Profissão:', 'Contato e Endereço'])
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    #[Test]
    public function assistantShouldAccessEmployeesDetailsPage(): void
    {
        $this->actingAs(self::$userAss);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        $this->get(route('employees.show', 1))
            ->assertSee(['Listar Colaboradores', 'Nome:', 'CPF:', 'Profissão:', 'Contato e Endereço'])
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    #[Test]
    public function secretaryShouldAccessEmployeesDetailsPage(): void
    {
        $this->actingAs(self::$userSec);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        $this->get(route('employees.show', 1))
            ->assertSee(['Listar Colaboradores', 'Nome:', 'CPF:', 'Profissão:', 'Contato e Endereço'])
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    #[Test]
    public function ldiShouldntAccessEmployeesDetailsPage(): void
    {
        $this->actingAs(self::$userLdi);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        $this->get(route('employees.show', 1))
            ->assertStatus(403);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    #[Test]
    public function coordinatorShouldntAccessEmployeesDetailsPage(): void
    {
        $this->actingAs(self::$userCoord);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        $this->get(route('employees.show', 1))
            ->assertStatus(403);
    }

    // ================= See Create Form Tests =================

    /**
     * Guest Shouldnt access create employee page
     *
     * @return void
     */
    #[Test]
    public function guestShouldntAccessCreateEmployeesPage(): void
    {
        $this->get(route('employees.create'))
            ->assertStatus(401);
    }

    /**
     * Authenticated user without permission Shouldnt Access create employee page
     *
     * @return void
     */
    #[Test]
    public function authenticatedUserWithoutPermissionShouldntAccessCreateEmployeesPage(): void
    {
        $this->actingAs(self::$userAlien)
            ->withSession(['loggedInUser.currentResponsibility' => null]);

        $this->get(route('employees.create'))
            ->assertStatus(403);
    }

    /**
     * Admin user Should access create employee page
     *
     * @return void
     */
    #[Test]
    public function administratorShouldAccessCreateEmployeesPage(): void
    {
        $this->actingAs(self::$userAdm);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        $this->get(route('employees.create'))
            ->assertSee(['Cadastrar Colaborador', 'Nome*', 'CPF*', 'Profissão', 'Cadastrar'])
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    #[Test]
    public function directorShouldAccessCreateEmployeesPage(): void
    {
        $this->actingAs(self::$userDir);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        $this->get(route('employees.create'))
            ->assertSee(['Cadastrar Colaborador', 'Nome*', 'CPF*', 'Profissão', 'Cadastrar'])
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    #[Test]
    public function assistantShouldAccessCreateEmployeesPage(): void
    {
        $this->actingAs(self::$userAss);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        $this->get(route('employees.create'))
            ->assertSee(['Cadastrar Colaborador', 'Nome*', 'CPF*', 'Profissão', 'Cadastrar'])
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    #[Test]
    public function secretaryShouldAccessCreateEmployeesPage(): void
    {
        $this->actingAs(self::$userSec);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        $this->get(route('employees.create'))
            ->assertSee(['Cadastrar Colaborador', 'Nome*', 'CPF*', 'Profissão', 'Cadastrar'])
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    #[Test]
    public function ldiShouldntAccessCreateEmployeesPage(): void
    {
        $this->actingAs(self::$userLdi);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        $this->get(route('employees.create'))
            ->assertStatus(403);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    #[Test]
    public function coordinatorShouldntAccessCreateEmployeesPage(): void
    {
        $this->actingAs(self::$userCoord);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        $this->get(route('employees.create'))
            ->assertStatus(403);
    }

    // ================= Create Employee Tests =================

    /**
     * Guest Shouldnt create employee
     *
     * @return void
     */
    #[Test]
    public function guestShouldntCreateEmployee(): void
    {
        $employeeArr = $this->createTestEmployeeArray();
        Arr::forget($employeeArr, ['id', 'created_at', 'updated_at']);
        $employeeArr = array_merge($employeeArr, $this->createTestBankAccountArr());

        $this->post(route('employees.store'), $employeeArr)
            ->assertStatus(401);
    }

    /**
     * Authenticated user without permission Shouldnt create employee
     *
     * @return void
     */
    #[Test]
    public function authenticatedUserWithoutPermissionShouldntCreateEmployee(): void
    {
        $employeeArr = $this->createTestEmployeeArray();
        Arr::forget($employeeArr, ['id', 'created_at', 'updated_at']);
        $employeeArr = array_merge($employeeArr, $this->createTestBankAccountArr());

        $this->actingAs(self::$userAlien)
            ->withSession(['loggedInUser.currentResponsibility' => null])
            ->followingRedirects()->post(route('employees.store'), $employeeArr)
            ->assertStatus(403);
    }

    /**
     * Admin user Should create employees
     *
     * @return void
     */
    #[Test]
    public function administratorShouldCreateEmployee(): void
    {
        $employeeArr = $this->createTestEmployeeArray();
        Arr::forget($employeeArr, ['id', 'created_at', 'updated_at']);
        $employeeArr = array_merge($employeeArr, $this->createTestBankAccountArr());

        $this->actingAs(self::$userAdm);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))])
            ->followingRedirects()->post(route('employees.store'), $employeeArr)
            ->assertSee($this->expectedEmployeeInfo())
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    #[Test]
    public function directorShouldCreateEmployee(): void
    {
        $this->actingAs(self::$userDir);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        $employeeArr = $this->createTestEmployeeArray();
        Arr::forget($employeeArr, ['id', 'created_at', 'updated_at']);
        $employeeArr = array_merge($employeeArr, $this->createTestBankAccountArr());

        $this->followingRedirects()->post(route('employees.store'), $employeeArr)
            ->assertSee($this->expectedEmployeeInfo())
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    #[Test]
    public function assistantShouldCreateEmployee(): void
    {
        $this->actingAs(self::$userAss);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        $employeeArr = $this->createTestEmployeeArray();
        Arr::forget($employeeArr, ['id', 'created_at', 'updated_at']);
        $employeeArr = array_merge($employeeArr, $this->createTestBankAccountArr());

        $this->followingRedirects()->post(route('employees.store'), $employeeArr)
            ->assertSee($this->expectedEmployeeInfo())
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    #[Test]
    public function secretaryShouldCreateEmployee(): void
    {
        $this->actingAs(self::$userSec);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        $employeeArr = $this->createTestEmployeeArray();
        Arr::forget($employeeArr, ['id', 'created_at', 'updated_at']);
        $employeeArr = array_merge($employeeArr, $this->createTestBankAccountArr());

        $this->followingRedirects()->post(route('employees.store'), $employeeArr)
            ->assertSee($this->expectedEmployeeInfo())
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    #[Test]
    public function coordinatorShouldntCreateEmployee(): void
    {
        $this->actingAs(self::$userCoord);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        $employeeArr = $this->createTestEmployeeArray();
        Arr::forget($employeeArr, ['id', 'created_at', 'updated_at']);
        $employeeArr = array_merge($employeeArr, $this->createTestBankAccountArr());

        $this->followingRedirects()->post(route('employees.store'), $employeeArr)
            ->assertStatus(403);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    #[Test]
    public function ldiShouldntCreateEmployee(): void
    {
        $this->actingAs(self::$userLdi);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        $employeeArr = $this->createTestEmployeeArray();
        Arr::forget($employeeArr, ['id', 'created_at', 'updated_at']);
        $employeeArr = array_merge($employeeArr, $this->createTestBankAccountArr());

        $this->followingRedirects()->post(route('employees.store'), $employeeArr)
            ->assertStatus(403);
    }

    // ================= See Edit Form Tests =================

    /**
     * Guest Shouldnt access edit employee page
     *
     * @return void
     */
    #[Test]
    public function guestShouldntAccessEditEmployeesPage(): void
    {
        $this->get(route('employees.edit', 1))
            ->assertStatus(401);
    }

    /**
     * Authenticated user without permission Shouldnt access edit employee page
     *
     * @return void
     */
    #[Test]
    public function authenticatedUserWithoutPermissionShouldntAccessEditEmployeesPage(): void
    {
        $this->actingAs(self::$userAlien)
            ->withSession(['loggedInUser.currentResponsibility' => null]);

        $this->get(route('employees.edit', 1))
            ->assertStatus(403);
    }

    /**
     * Admin user Should access edit employee page
     *
     * @return void
     */
    #[Test]
    public function administratorShouldAccessEditEmployeesPage(): void
    {
        $this->actingAs(self::$userAdm);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        $this->get(route('employees.edit', 1))
            ->assertSee(['Editar', 'Nome*', 'CPF*', 'Profissão', 'Atualizar'])
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    #[Test]
    public function directorShouldAccessEditEmployeesPage(): void
    {
        $this->actingAs(self::$userDir);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        $this->get(route('employees.edit', 1))
            ->assertSee(['Editar', 'Nome*', 'CPF*', 'Profissão', 'Atualizar'])
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    #[Test]
    public function assistantShouldAccessEditEmployeesPage(): void
    {
        $this->actingAs(self::$userAss);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        $this->get(route('employees.edit', 1))
            ->assertSee(['Editar', 'Nome*', 'CPF*', 'Profissão', 'Atualizar'])
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    #[Test]
    public function secretaryShouldAccessEditEmployeesPage(): void
    {
        $this->actingAs(self::$userSec);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        $this->get(route('employees.edit', 1))
            ->assertSee(['Editar', 'Nome*', 'CPF*', 'Profissão', 'Atualizar'])
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    #[Test]
    public function ldiShouldntAccessEditEmployeesPage(): void
    {
        $this->actingAs(self::$userLdi);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        $this->get(route('employees.edit', 1))
            ->assertStatus(403);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    #[Test]
    public function coordinatorShouldntAccessEditEmployeesPage(): void
    {
        $this->actingAs(self::$userCoord);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        $this->get(route('employees.edit', 1))
            ->assertStatus(403);
    }

    // ================= Update Employee Tests =================

    /**
     * Guest Shouldnt update employee
     *
     * @return void
     */
    #[Test]
    public function guestShouldntUpdateEmployee(): void
    {
        /** @var Employee $originalEmployee */
        $originalEmployee = Employee::where('name', 'John Doe')->first();
        $originalBankAccount = BankAccount::where('employee_id', $originalEmployee->id)->first();
        $originalEmployeeArr = $originalEmployee->toArray();
        Arr::forget($originalEmployeeArr, ['id', 'created_at', 'updated_at']);

        $originalEmployeeArr['name'] = $this->updatedEmployeeData()['name'];
        $originalEmployeeArr['email'] = $this->updatedEmployeeData()['email'];
        $originalEmployeeArr['bank_name'] = $originalBankAccount?->bank_name;
        $originalEmployeeArr['agency_number'] = $originalBankAccount?->agency_number;
        $originalEmployeeArr['account_number'] = $originalBankAccount?->account;

        $this->put(route('employees.update', $originalEmployee->id), $originalEmployeeArr)
            ->assertStatus(401);
    }

    /**
     * Authenticated user without permission Shouldnt update employee
     *
     * @return void
     */
    #[Test]
    public function authenticatedUserWithoutPermissionShouldntUpdateEmployee(): void
    {
        $this->actingAs(self::$userAlien)
            ->withSession(['loggedInUser.currentResponsibility' => null]);

        /** @var Employee $originalEmployee */
        $originalEmployee = Employee::where('name', 'John Doe')->first();
        $originalBankAccount = BankAccount::where('employee_id', $originalEmployee->id)->first();
        $originalEmployeeArr = $originalEmployee->toArray();
        Arr::forget($originalEmployeeArr, ['id', 'created_at', 'updated_at']);

        $originalEmployeeArr['name'] = $this->updatedEmployeeData()['name'];
        $originalEmployeeArr['email'] = $this->updatedEmployeeData()['email'];
        $originalEmployeeArr['bank_name'] = $originalBankAccount?->bank_name;
        $originalEmployeeArr['agency_number'] = $originalBankAccount?->agency_number;
        $originalEmployeeArr['account_number'] = $originalBankAccount?->account;

        $this->put(route('employees.update', $originalEmployee->id), $originalEmployeeArr)
            ->assertStatus(403);
    }

    /**
     * Admin user Should update employee
     *
     * @return void
     */
    #[Test]
    public function administratorShouldUpdateEmployee(): void
    {
        $this->actingAs(self::$userAdm);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        /** @var Employee $originalEmployee */
        $originalEmployee = Employee::where('name', 'John Doe')->first();
        $originalBankAccount = BankAccount::where('employee_id', $originalEmployee->id)->first();
        $originalEmployeeArr = $originalEmployee->toArray();
        Arr::forget($originalEmployeeArr, ['id', 'created_at', 'updated_at']);

        $originalEmployeeArr['name'] = $this->updatedEmployeeData()['name'];
        $originalEmployeeArr['email'] = $this->updatedEmployeeData()['email'];
        $originalEmployeeArr['cpf_number'] = $originalEmployeeArr['cpf'];
        $originalEmployeeArr['job'] = $originalEmployee->personalDetail?->job ?? 'New Job';
        $originalEmployeeArr['birth_date'] = $originalEmployee->personalDetail?->birth_date ?? strval(Carbon::now()->subYears(30));
        $originalEmployeeArr['birth_state'] = $originalEmployee->personalDetail?->birth_state ?? $this->faker->randomElement(States::cases())->name;
        $originalEmployeeArr['birth_city'] = $originalEmployee->personalDetail?->birth_city ?? 'New City';
        $originalEmployeeArr['marital_status'] = $originalEmployee->personalDetail?->marital_status ?? $this->faker->randomElement(MaritalStatuses::cases())->name;
        $originalEmployeeArr['father_name'] = $originalEmployee->personalDetail?->father_name ?? 'New Father Name';
        $originalEmployeeArr['mother_name'] = $originalEmployee->personalDetail?->mother_name ?? 'New Mother Name';
        $originalEmployeeArr['document_type_id'] = $originalEmployee->identity?->type_id ?? DocumentType::select('id')->limit(1)->get()->first()->id;
        $originalEmployeeArr['identity_number'] = $originalEmployee->identity?->number ?? '99966655544';
        $originalEmployeeArr['identity_issuer'] = $originalEmployee->identity?->issuer ?? 'New Issuer';
        $originalEmployeeArr['issuer_state'] = $originalEmployee->identity?->issuer_state ?? $this->faker->randomElement(States::cases())->name;
        $originalEmployeeArr['identity_issue_date'] = $originalEmployee->identity?->issue_date ?? strval(Carbon::now()->subYears(10));
        $originalEmployeeArr['address_street'] = $originalEmployee->address?->street ?? 'New Street';
        $originalEmployeeArr['address_number'] = $originalEmployee->address?->number ?? '999';
        $originalEmployeeArr['address_complement'] = $originalEmployee->address?->complement ?? 'New Complement';
        $originalEmployeeArr['address_district'] = $originalEmployee->address?->district ?? 'New district';
        $originalEmployeeArr['address_state'] = $originalEmployee->address?->state ?? $this->faker->randomElement(States::cases())->name;
        $originalEmployeeArr['address_city'] = $originalEmployee->address?->city ?? 'New City';
        $originalEmployeeArr['address_zip_code'] = $originalEmployee->address?->zip_code ?? '99999999';
        $originalEmployeeArr['landline'] = $originalEmployee->phone?->landline ?? '2739999999';
        $originalEmployeeArr['mobile'] = $originalEmployee->phone?->mobile ?? '2799999999';
        $originalEmployeeArr['area_code'] = $originalEmployee->phone?->area_code ?? '27';
        $originalEmployeeArr['bank_name'] = $originalBankAccount?->bank_name;
        $originalEmployeeArr['agency_number'] = $originalBankAccount?->agency_number;
        $originalEmployeeArr['account_number'] = $originalBankAccount?->account;

        $this->followingRedirects()->put(route('employees.update', $originalEmployee->id), $originalEmployeeArr)
            ->assertSee($this->updatedEmployeeData())
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    #[Test]
    public function directorShouldUpdateEmployee(): void
    {
        $this->actingAs(self::$userDir);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        /** @var Employee $originalEmployee */
        $originalEmployee = Employee::where('name', 'John Doe')->first();
        $originalBankAccount = BankAccount::where('employee_id', $originalEmployee->id)->first();
        $originalEmployeeArr = $originalEmployee->toArray();
        Arr::forget($originalEmployeeArr, ['id', 'created_at', 'updated_at']);


        $originalEmployeeArr['name'] = $this->updatedEmployeeData()['name'];
        $originalEmployeeArr['email'] = $this->updatedEmployeeData()['email'];
        $originalEmployeeArr['cpf_number'] = $originalEmployeeArr['cpf'];
        $originalEmployeeArr['job'] = $originalEmployee->personalDetail?->job ?? 'New Job';
        $originalEmployeeArr['birth_date'] = $originalEmployee->personalDetail?->birth_date ?? strval(Carbon::now()->subYears(30));
        $originalEmployeeArr['birth_state'] = $originalEmployee->personalDetail?->birth_state ?? $this->faker->randomElement(States::cases())->name;
        $originalEmployeeArr['birth_city'] = $originalEmployee->personalDetail?->birth_city ?? 'New City';
        $originalEmployeeArr['marital_status'] = $originalEmployee->personalDetail?->marital_status ?? $this->faker->randomElement(MaritalStatuses::cases())->name;
        $originalEmployeeArr['father_name'] = $originalEmployee->personalDetail?->father_name ?? 'New Father Name';
        $originalEmployeeArr['mother_name'] = $originalEmployee->personalDetail?->mother_name ?? 'New Mother Name';
        $originalEmployeeArr['document_type_id'] = $originalEmployee->identity?->type_id ?? DocumentType::select('id')->limit(1)->get()->first()->id;
        $originalEmployeeArr['identity_number'] = $originalEmployee->identity?->number ?? '99966655544';
        $originalEmployeeArr['identity_issuer'] = $originalEmployee->identity?->issuer ?? 'New Issuer';
        $originalEmployeeArr['issuer_state'] = $originalEmployee->identity?->issuer_state ?? $this->faker->randomElement(States::cases())->name;
        $originalEmployeeArr['identity_issue_date'] = $originalEmployee->identity?->issue_date ?? strval(Carbon::now()->subYears(10));
        $originalEmployeeArr['address_street'] = $originalEmployee->address?->street ?? 'New Street';
        $originalEmployeeArr['address_number'] = $originalEmployee->address?->number ?? '999';
        $originalEmployeeArr['address_complement'] = $originalEmployee->address?->complement ?? 'New Complement';
        $originalEmployeeArr['address_district'] = $originalEmployee->address?->district ?? 'New district';
        $originalEmployeeArr['address_state'] = $originalEmployee->address?->state ?? $this->faker->randomElement(States::cases())->name;
        $originalEmployeeArr['address_city'] = $originalEmployee->address?->city ?? 'New City';
        $originalEmployeeArr['address_zip_code'] = $originalEmployee->address?->zip_code ?? '99999999';
        $originalEmployeeArr['landline'] = $originalEmployee->phone?->landline ?? '2739999999';
        $originalEmployeeArr['mobile'] = $originalEmployee->phone?->mobile ?? '2799999999';
        $originalEmployeeArr['area_code'] = $originalEmployee->phone?->area_code ?? '27';
        $originalEmployeeArr['bank_name'] = $originalBankAccount?->bank_name;
        $originalEmployeeArr['agency_number'] = $originalBankAccount?->agency_number;
        $originalEmployeeArr['account_number'] = $originalBankAccount?->account;

        $this->followingRedirects()->put(route('employees.update', $originalEmployee->id), $originalEmployeeArr)
            ->assertSee($this->updatedEmployeeData())
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    #[Test]
    public function assistantShouldUpdateEmployee(): void
    {
        $this->actingAs(self::$userAss);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        /** @var Employee $originalEmployee */
        $originalEmployee = Employee::where('name', 'John Doe')->first();
        $originalBankAccount = BankAccount::where('employee_id', $originalEmployee->id)->first();
        $originalEmployeeArr = $originalEmployee->toArray();
        Arr::forget($originalEmployeeArr, ['id', 'created_at', 'updated_at']);


        $originalEmployeeArr['name'] = $this->updatedEmployeeData()['name'];
        $originalEmployeeArr['email'] = $this->updatedEmployeeData()['email'];
        $originalEmployeeArr['cpf_number'] = $originalEmployeeArr['cpf'];
        $originalEmployeeArr['job'] = $originalEmployee->personalDetail?->job ?? 'New Job';
        $originalEmployeeArr['birth_date'] = $originalEmployee->personalDetail?->birth_date ?? strval(Carbon::now()->subYears(30));
        $originalEmployeeArr['birth_state'] = $originalEmployee->personalDetail?->birth_state ?? $this->faker->randomElement(States::cases())->name;
        $originalEmployeeArr['birth_city'] = $originalEmployee->personalDetail?->birth_city ?? 'New City';
        $originalEmployeeArr['marital_status'] = $originalEmployee->personalDetail?->marital_status ?? $this->faker->randomElement(MaritalStatuses::cases())->name;
        $originalEmployeeArr['father_name'] = $originalEmployee->personalDetail?->father_name ?? 'New Father Name';
        $originalEmployeeArr['mother_name'] = $originalEmployee->personalDetail?->mother_name ?? 'New Mother Name';
        $originalEmployeeArr['document_type_id'] = $originalEmployee->identity?->type_id ?? DocumentType::select('id')->limit(1)->get()->first()->id;
        $originalEmployeeArr['identity_number'] = $originalEmployee->identity?->number ?? '99966655544';
        $originalEmployeeArr['identity_issuer'] = $originalEmployee->identity?->issuer ?? 'New Issuer';
        $originalEmployeeArr['issuer_state'] = $originalEmployee->identity?->issuer_state ?? $this->faker->randomElement(States::cases())->name;
        $originalEmployeeArr['identity_issue_date'] = $originalEmployee->identity?->issue_date ?? strval(Carbon::now()->subYears(10));
        $originalEmployeeArr['address_street'] = $originalEmployee->address?->street ?? 'New Street';
        $originalEmployeeArr['address_number'] = $originalEmployee->address?->number ?? '999';
        $originalEmployeeArr['address_complement'] = $originalEmployee->address?->complement ?? 'New Complement';
        $originalEmployeeArr['address_district'] = $originalEmployee->address?->district ?? 'New district';
        $originalEmployeeArr['address_state'] = $originalEmployee->address?->state ?? $this->faker->randomElement(States::cases())->name;
        $originalEmployeeArr['address_city'] = $originalEmployee->address?->city ?? 'New City';
        $originalEmployeeArr['address_zip_code'] = $originalEmployee->address?->zip_code ?? '99999999';
        $originalEmployeeArr['landline'] = $originalEmployee->phone?->landline ?? '2739999999';
        $originalEmployeeArr['mobile'] = $originalEmployee->phone?->mobile ?? '2799999999';
        $originalEmployeeArr['area_code'] = $originalEmployee->phone?->area_code ?? '27';
        $originalEmployeeArr['bank_name'] = $originalBankAccount?->bank_name;
        $originalEmployeeArr['agency_number'] = $originalBankAccount?->agency_number;
        $originalEmployeeArr['account_number'] = $originalBankAccount?->account;

        $this->followingRedirects()->put(route('employees.update', $originalEmployee->id), $originalEmployeeArr)
            ->assertSee($this->updatedEmployeeData())
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    #[Test]
    public function secretaryShouldUpdateEmployee(): void
    {
        $this->actingAs(self::$userSec);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        /** @var Employee $originalEmployee */
        $originalEmployee = Employee::where('name', 'John Doe')->first();
        $originalBankAccount = BankAccount::where('employee_id', $originalEmployee->id)->first();
        $originalEmployeeArr = $originalEmployee->toArray();
        Arr::forget($originalEmployeeArr, ['id', 'created_at', 'updated_at']);


        $originalEmployeeArr['name'] = $this->updatedEmployeeData()['name'];
        $originalEmployeeArr['email'] = $this->updatedEmployeeData()['email'];
        $originalEmployeeArr['cpf_number'] = $originalEmployeeArr['cpf'];
        $originalEmployeeArr['job'] = $originalEmployee->personalDetail?->job ?? 'New Job';
        $originalEmployeeArr['birth_date'] = $originalEmployee->personalDetail?->birth_date ?? strval(Carbon::now()->subYears(30));
        $originalEmployeeArr['birth_state'] = $originalEmployee->personalDetail?->birth_state ?? $this->faker->randomElement(States::cases())->name;
        $originalEmployeeArr['birth_city'] = $originalEmployee->personalDetail?->birth_city ?? 'New City';
        $originalEmployeeArr['marital_status'] = $originalEmployee->personalDetail?->marital_status ?? $this->faker->randomElement(MaritalStatuses::cases())->name;
        $originalEmployeeArr['father_name'] = $originalEmployee->personalDetail?->father_name ?? 'New Father Name';
        $originalEmployeeArr['mother_name'] = $originalEmployee->personalDetail?->mother_name ?? 'New Mother Name';
        $originalEmployeeArr['document_type_id'] = $originalEmployee->identity?->type_id ?? DocumentType::select('id')->limit(1)->get()->first()->id;
        $originalEmployeeArr['identity_number'] = $originalEmployee->identity?->number ?? '99966655544';
        $originalEmployeeArr['identity_issuer'] = $originalEmployee->identity?->issuer ?? 'New Issuer';
        $originalEmployeeArr['issuer_state'] = $originalEmployee->identity?->issuer_state ?? $this->faker->randomElement(States::cases())->name;
        $originalEmployeeArr['identity_issue_date'] = $originalEmployee->identity?->issue_date ?? strval(Carbon::now()->subYears(10));
        $originalEmployeeArr['address_street'] = $originalEmployee->address?->street ?? 'New Street';
        $originalEmployeeArr['address_number'] = $originalEmployee->address?->number ?? '999';
        $originalEmployeeArr['address_complement'] = $originalEmployee->address?->complement ?? 'New Complement';
        $originalEmployeeArr['address_district'] = $originalEmployee->address?->district ?? 'New district';
        $originalEmployeeArr['address_state'] = $originalEmployee->address?->state ?? $this->faker->randomElement(States::cases())->name;
        $originalEmployeeArr['address_city'] = $originalEmployee->address?->city ?? 'New City';
        $originalEmployeeArr['address_zip_code'] = $originalEmployee->address?->zip_code ?? '99999999';
        $originalEmployeeArr['landline'] = $originalEmployee->phone?->landline ?? '2739999999';
        $originalEmployeeArr['mobile'] = $originalEmployee->phone?->mobile ?? '2799999999';
        $originalEmployeeArr['area_code'] = $originalEmployee->phone?->area_code ?? '27';
        $originalEmployeeArr['bank_name'] = $originalBankAccount?->bank_name;
        $originalEmployeeArr['agency_number'] = $originalBankAccount?->agency_number;
        $originalEmployeeArr['account_number'] = $originalBankAccount?->account;

        $this->followingRedirects()->put(route('employees.update', $originalEmployee->id), $originalEmployeeArr)
            ->assertSee($this->updatedEmployeeData())
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    #[Test]
    public function coordinatorShouldntUpdateEmployee(): void
    {
        $this->actingAs(self::$userCoord);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        /** @var Employee $originalEmployee */
        $originalEmployee = Employee::where('name', 'John Doe')->first();
        $originalBankAccount = BankAccount::where('employee_id', $originalEmployee->id)->first();
        $originalEmployeeArr = $originalEmployee->toArray();
        Arr::forget($originalEmployeeArr, ['id', 'created_at', 'updated_at']);


        $originalEmployeeArr['name'] = $this->updatedEmployeeData()['name'];
        $originalEmployeeArr['email'] = $this->updatedEmployeeData()['email'];
        $originalEmployeeArr['cpf_number'] = $originalEmployeeArr['cpf'];
        $originalEmployeeArr['job'] = $originalEmployee->personalDetail?->job ?? 'New Job';
        $originalEmployeeArr['birth_date'] = $originalEmployee->personalDetail?->birth_date ?? strval(Carbon::now()->subYears(30));
        $originalEmployeeArr['birth_state'] = $originalEmployee->personalDetail?->birth_state ?? $this->faker->randomElement(States::cases())->name;
        $originalEmployeeArr['birth_city'] = $originalEmployee->personalDetail?->birth_city ?? 'New City';
        $originalEmployeeArr['marital_status'] = $originalEmployee->personalDetail?->marital_status ?? $this->faker->randomElement(MaritalStatuses::cases())->name;
        $originalEmployeeArr['father_name'] = $originalEmployee->personalDetail?->father_name ?? 'New Father Name';
        $originalEmployeeArr['mother_name'] = $originalEmployee->personalDetail?->mother_name ?? 'New Mother Name';
        $originalEmployeeArr['document_type_id'] = $originalEmployee->identity?->type_id ?? DocumentType::select('id')->limit(1)->get()->first()->id;
        $originalEmployeeArr['identity_number'] = $originalEmployee->identity?->number ?? '99966655544';
        $originalEmployeeArr['identity_issuer'] = $originalEmployee->identity?->issuer ?? 'New Issuer';
        $originalEmployeeArr['issuer_state'] = $originalEmployee->identity?->issuer_state ?? $this->faker->randomElement(States::cases())->name;
        $originalEmployeeArr['identity_issue_date'] = $originalEmployee->identity?->issue_date ?? strval(Carbon::now()->subYears(10));
        $originalEmployeeArr['address_street'] = $originalEmployee->address?->street ?? 'New Street';
        $originalEmployeeArr['address_number'] = $originalEmployee->address?->number ?? '999';
        $originalEmployeeArr['address_complement'] = $originalEmployee->address?->complement ?? 'New Complement';
        $originalEmployeeArr['address_district'] = $originalEmployee->address?->district ?? 'New district';
        $originalEmployeeArr['address_state'] = $originalEmployee->address?->state ?? $this->faker->randomElement(States::cases())->name;
        $originalEmployeeArr['address_city'] = $originalEmployee->address?->city ?? 'New City';
        $originalEmployeeArr['address_zip_code'] = $originalEmployee->address?->zip_code ?? '99999999';
        $originalEmployeeArr['landline'] = $originalEmployee->phone?->landline ?? '2739999999';
        $originalEmployeeArr['mobile'] = $originalEmployee->phone?->mobile ?? '2799999999';
        $originalEmployeeArr['area_code'] = $originalEmployee->phone?->area_code ?? '27';
        $originalEmployeeArr['bank_name'] = $originalBankAccount?->bank_name;
        $originalEmployeeArr['agency_number'] = $originalBankAccount?->agency_number;
        $originalEmployeeArr['account_number'] = $originalBankAccount?->account;

        $this->put(route('employees.update', $originalEmployee->id), $originalEmployeeArr)
            ->assertStatus(403);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    #[Test]
    public function ldiShouldntUpdateEmployee(): void
    {
        $this->actingAs(self::$userLdi);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        /** @var Employee $originalEmployee */
        $originalEmployee = Employee::where('name', 'John Doe')->first();
        $originalBankAccount = BankAccount::where('employee_id', $originalEmployee->id)->first();
        $originalEmployeeArr = $originalEmployee->toArray();
        Arr::forget($originalEmployeeArr, ['id', 'created_at', 'updated_at']);


        $originalEmployeeArr['name'] = $this->updatedEmployeeData()['name'];
        $originalEmployeeArr['email'] = $this->updatedEmployeeData()['email'];
        $originalEmployeeArr['cpf_number'] = $originalEmployeeArr['cpf'];
        $originalEmployeeArr['job'] = $originalEmployee->personalDetail?->job ?? 'New Job';
        $originalEmployeeArr['birth_date'] = $originalEmployee->personalDetail?->birth_date ?? strval(Carbon::now()->subYears(30));
        $originalEmployeeArr['birth_state'] = $originalEmployee->personalDetail?->birth_state ?? $this->faker->randomElement(States::cases())->name;
        $originalEmployeeArr['birth_city'] = $originalEmployee->personalDetail?->birth_city ?? 'New City';
        $originalEmployeeArr['marital_status'] = $originalEmployee->personalDetail?->marital_status ?? $this->faker->randomElement(MaritalStatuses::cases())->name;
        $originalEmployeeArr['father_name'] = $originalEmployee->personalDetail?->father_name ?? 'New Father Name';
        $originalEmployeeArr['mother_name'] = $originalEmployee->personalDetail?->mother_name ?? 'New Mother Name';
        $originalEmployeeArr['document_type_id'] = $originalEmployee->identity?->type_id ?? DocumentType::select('id')->limit(1)->get()->first()->id;
        $originalEmployeeArr['identity_number'] = $originalEmployee->identity?->number ?? '99966655544';
        $originalEmployeeArr['identity_issuer'] = $originalEmployee->identity?->issuer ?? 'New Issuer';
        $originalEmployeeArr['issuer_state'] = $originalEmployee->identity?->issuer_state ?? $this->faker->randomElement(States::cases())->name;
        $originalEmployeeArr['identity_issue_date'] = $originalEmployee->identity?->issue_date ?? strval(Carbon::now()->subYears(10));
        $originalEmployeeArr['address_street'] = $originalEmployee->address?->street ?? 'New Street';
        $originalEmployeeArr['address_number'] = $originalEmployee->address?->number ?? '999';
        $originalEmployeeArr['address_complement'] = $originalEmployee->address?->complement ?? 'New Complement';
        $originalEmployeeArr['address_district'] = $originalEmployee->address?->district ?? 'New district';
        $originalEmployeeArr['address_state'] = $originalEmployee->address?->state ?? $this->faker->randomElement(States::cases())->name;
        $originalEmployeeArr['address_city'] = $originalEmployee->address?->city ?? 'New City';
        $originalEmployeeArr['address_zip_code'] = $originalEmployee->address?->zip_code ?? '99999999';
        $originalEmployeeArr['landline'] = $originalEmployee->phone?->landline ?? '2739999999';
        $originalEmployeeArr['mobile'] = $originalEmployee->phone?->mobile ?? '2799999999';
        $originalEmployeeArr['area_code'] = $originalEmployee->phone?->area_code ?? '27';
        $originalEmployeeArr['bank_name'] = $originalBankAccount?->bank_name;
        $originalEmployeeArr['agency_number'] = $originalBankAccount?->agency_number;
        $originalEmployeeArr['account_number'] = $originalBankAccount?->account;

        $this->put(route('employees.update', $originalEmployee->id), $originalEmployeeArr)
            ->assertStatus(403);
    }

    // ================= Delete Employee Tests =================

    /**
     * Guest Shouldnt delete employee
     *
     * @return void
     */
    #[Test]
    public function guestShouldntDeleteEmployees(): void
    {
        $employeeBefore = Employee::find(1);

        $this->delete(route('employees.destroy', 1))
            ->assertStatus(401);

        $employeeAfter = Employee::find(1);
        $this->assertEquals($employeeBefore, $employeeAfter);
    }

    /**
     * Authenticated user without permission Shouldnt delete employee
     *
     * @return void
     */
    #[Test]
    public function authenticatedUserWithoutPermissionShouldntDeleteEmployees(): void
    {
        $this->actingAs(self::$userAlien)
            ->withSession(['loggedInUser.currentResponsibility' => null]);

        $employeeBefore = Employee::find(1);

        $this->delete(route('employees.destroy', 1))
            ->assertStatus(403);

        $employeeAfter = Employee::find(1);
        $this->assertEquals($employeeBefore, $employeeAfter);
    }

    /**
     * Admin user Should delete employee
     *
     * @return void
     */
    #[Test]
    public function administratorShouldDeleteEmployees(): void
    {
        $this->actingAs(self::$userAdm);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        $employeeBefore = Employee::find(1);

        $this->followingRedirects()->delete(route('employees.destroy', 1))
            ->assertStatus(200);

        $employeeAfter = Employee::find(1);

        $this->assertNotNull($employeeBefore);
        $this->assertNull($employeeAfter);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    #[Test]
    public function directorShouldntDeleteEmployees(): void
    {
        $this->actingAs(self::$userDir);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        $employeeBefore = Employee::find(1);

        $this->delete(route('employees.destroy', 1))
            ->assertStatus(403);

        $employeeAfter = Employee::find(1);
        $this->assertEquals($employeeBefore, $employeeAfter);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    #[Test]
    public function assistantShouldntDeleteEmployees(): void
    {
        $this->actingAs(self::$userAss);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        $employeeBefore = Employee::find(1);

        $this->delete(route('employees.destroy', 1))
            ->assertStatus(403);

        $employeeAfter = Employee::find(1);
        $this->assertEquals($employeeBefore, $employeeAfter);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    #[Test]
    public function secretaryShouldntDeleteEmployees(): void
    {
        $this->actingAs(self::$userSec);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        $employeeBefore = Employee::find(1);

        $this->delete(route('employees.destroy', 1))
            ->assertStatus(403);

        $employeeAfter = Employee::find(1);
        $this->assertEquals($employeeBefore, $employeeAfter);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    #[Test]
    public function ldiShouldntDeleteEmployees(): void
    {
        $this->actingAs(self::$userLdi);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        $employeeBefore = Employee::find(1);

        $this->delete(route('employees.destroy', 1))
            ->assertStatus(403);

        $employeeAfter = Employee::find(1);
        $this->assertEquals($employeeBefore, $employeeAfter);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    #[Test]
    public function coordinatorShouldntDeleteEmployees(): void
    {
        $this->actingAs(self::$userCoord);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        $employeeBefore = Employee::find(1);

        $this->delete(route('employees.destroy', 1))
            ->assertStatus(403);

        $employeeAfter = Employee::find(1);
        $this->assertEquals($employeeBefore, $employeeAfter);
    }

    /**
     * @mixin \Faker\Generator
     *
     * @return array<string, string>
     */
    private function createTestEmployeeArray(): array
    {
        $generator = $this->faker->unique();

        /** @phpstan-ignore-next-line */
        $cpf = $generator->cpf($formatted = false);

        return [
            'name' => 'Carl Doe',
            'gender' => 'M',
            'email' => strval((debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2)[1]['function']) . '@test-case.com'),
            'cpf_number' => $cpf,
            'job' => 'carldoejob',
            'birth_date' => strval(Carbon::now()->subYears(30)),
            'birth_state' => $this->faker->randomElement(States::cases())->name,
            'birth_city' => 'New City',
            'marital_status' => $this->faker->randomElement(MaritalStatuses::cases())->name,
            'father_name' => 'New Father Name',
            'mother_name' => 'New Mother Name',
            'document_type_id' => strval(DocumentType::select('id')->limit(1)->get()->first()->id),
            'identity_number' => '99966655544',
            'identity_issuer' => 'New Issuer',
            'issuer_state' => $this->faker->randomElement(States::cases())->name,
            'identity_issue_date' => strval(Carbon::now()->subYears(10)),
            'address_street' => 'New Street',
            'address_number' => '999',
            'address_complement' => 'New Complement',
            'address_district' => 'New district',
            'address_state' =>  $this->faker->randomElement(States::cases())->name,
            'address_city' => 'New City',
            'address_zip_code' => '99999999',
            'landline' => '2739999999',
            'mobile' => '2799999999',
            'area_code' => '27',
        ];
    }

    /**
     * @return array<string, string>
     */
    private function createTestBankAccountArr(): array
    {
        $generator = $this->faker->unique();

        return [
            'bank_name' => 'Test Bank',
            'agency_number' => (string) $generator->numberBetween(1000, 9999),
            'account_number' => (string) $generator->numberBetween(1000, 9999),
        ];
    }

    /** @return array<string>  */
    private function expectedEmployeeInfo(): array
    {
        return ['Carl Doe'];
    }

    /** @return array<string>  */
    private function updatedEmployeeData(): array
    {
        return [
            'name' => 'John Doe Updated',
            'email' => mb_strtolower(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2)[1]['function']) . '@test-case.com',
        ];
    }

    //
    // TODO: move mock user functions to factory or helper
    //
}
