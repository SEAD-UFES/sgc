<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Employee;
use App\Models\User;
use App\Models\UserType;
use App\Models\UserTypeAssignment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class EmployeeTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    private static $userAdm;
    private static $userDir;
    private static $userAss;
    private static $userSec;
    private static $userCoord;
    private static $userLdi;
    private static $userAlien;

    private $employeeData;
    private $newEmployeeData;

    public function setUp(): void
    {
        parent::setUp();

        self::$userAdm = User::factory()->create(
            [
                'email' => 'adm_email@test.com',
            ]
        );
        $userTypeAdm = UserType::factory()->admin()->create();
        UserTypeAssignment::factory()->create([
            'user_id' => self::$userAdm->id,
            'user_type_id' => $userTypeAdm->id,
            'course_id' => null,
        ]);

        self::$userDir = User::factory()->create(
            [
                'email' => 'dir_email@test.com',
            ]
        );
        $userTypeDir = UserType::factory()->director()->create();
        UserTypeAssignment::factory()->create([
            'user_id' => self::$userDir->id,
            'user_type_id' => $userTypeDir->id,
            'course_id' => null,
        ]);

        self::$userAss = User::factory()->create(
            [
                'email' => 'ass_email@test.com',
            ]
        );
        $userTypeAss = UserType::factory()->assistant()->create();
        UserTypeAssignment::factory()->create([
            'user_id' => self::$userAss->id,
            'user_type_id' => $userTypeAss->id,
            'course_id' => null,
        ]);

        self::$userSec = User::factory()->create(
            [
                'email' => 'sec_email@test.com',
            ]
        );
        $userTypeSec = UserType::factory()->secretary()->create();
        UserTypeAssignment::factory()->create([
            'user_id' => self::$userSec->id,
            'user_type_id' => $userTypeSec->id,
            'course_id' => null,
        ]);

        self::$userCoord = User::factory()->create(
            [
                'email' => 'coord_email@test.com',
            ]
        );
        $userTypeCoord = UserType::factory()->coordinator()->create();
        UserTypeAssignment::factory()->create([
            'user_id' => self::$userCoord->id,
            'user_type_id' => $userTypeCoord->id,
            'course_id' => Course::factory()->create()->id,
        ]);

        self::$userLdi = User::factory()->create(
            [
                'email' => 'ldi_email@test.com',
            ]
        );
        $userTypeLdi = UserType::factory()->ldi()->create();
        UserTypeAssignment::factory()->create([
            'user_id' => self::$userLdi->id,
            'user_type_id' => $userTypeLdi->id,
            'course_id' => null,
        ]);

        self::$userAlien = User::factory()->create(
            [
                'email' => 'alien_email@test.com',
            ]
        );
        $userTypeAlien = UserType::factory()->alien()->create();
        UserTypeAssignment::factory()->create([
            'user_id' => self::$userAlien->id,
            'user_type_id' => $userTypeAlien->id,
            'course_id' => null,
        ]);

        Employee::factory()->create(
            [
                'name' => 'John Doe',
            ]
        );

        Employee::factory()->create(
            [
                'name' => 'Jane Doe',
            ]
        );

        $this->employeeData = [
            'name' => 'Fulano de Tal',
            'cpf' => $this->faker->unique()->cpf($formatted = false),
            'job'  => 'fulanodetal@mail.com',
            'genders' => '',
            'birthday' => '',
            'birthStates' => '',
            'birthCity' => '',
            'idNumber' => '',
            'documentTypes' => '',
            'idIssueDate' => '',
            'idIssueAgency' => '',
            'maritalStatuses' => '',
            'spouseName' => '',
            'fatherName' => '',
            'motherName' => '',
            'addressStreet' => '',
            'addressComplement' => '',
            'addressNumber' => '',
            'addressDistrict' => '',
            'addressPostalCode' => '',
            'addressStates' => '',
            'addressCity' => '',
            'areaCode' => '',
            'phone' => '',
            'mobile' => '',
            'email' => 'fulanodetal@mail.com',
        ];

        $this->newEmployeeData = [
            'name' => 'updated',
            'cpf' => $this->faker->unique()->cpf($formatted = false),
            'job'  => 'fulanodetal@mail.com',
            'genders' => '',
            'birthday' => '',
            'birthStates' => '',
            'birthCity' => '',
            'idNumber' => '',
            'documentTypes' => '',
            'idIssueDate' => '',
            'idIssueAgency' => '',
            'maritalStatuses' => '',
            'spouseName' => '',
            'fatherName' => '',
            'motherName' => '',
            'addressStreet' => '',
            'addressComplement' => '',
            'addressNumber' => '',
            'addressDistrict' => '',
            'addressPostalCode' => '',
            'addressStates' => '',
            'addressCity' => '',
            'areaCode' => '',
            'phone' => '',
            'mobile' => '',
            'email' => 'updated@email.com',
        ];
    }

    /**
     * Guest Shouldnt list employees
     * @return void
     * @test
     */
    public function guestShouldntListEmployees()
    {
        $this->get(route('employees.index'))
            ->assertRedirect(route('auth.login'));
    }

    /**
     * Authenticated user without permission Shouldnt list employees
     * @return void
     * @test
     */
    public function authenticatedUserWithoutPermissionShouldntListEmployees()
    {
        $this->actingAs(self::$userAlien)
            ->withSession(['loggedInUser.currentUta' => null, 'loggedInUser.currentUtaId' => null]);

        $this->get(route('employees.index'))
            ->assertStatus(403);
    }

    /**
     * Admin user Should list employees
     * @return void
     * @test
     */
    public function administratorShouldListEmployees()
    {
        $this->actingAs(self::$userAdm)
            ->withSession(['loggedInUser.currentUta' => auth()->user()->getFirstUta(),]);

        $this->get(route('employees.index'))
            ->assertSee('Listar Colaboradores')
            ->assertSee(['John Doe', 'Jane Doe'])
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function directorShouldListEmployees()
    {
        $this->actingAs(self::$userDir)
            ->withSession(['loggedInUser.currentUta' => auth()->user()->getFirstUta(),]);

        $this->get(route('employees.index'))
            ->assertSee(['John Doe', 'Jane Doe'])
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function assistantShouldListEmployees()
    {
        $this->actingAs(self::$userAss)
            ->withSession(['loggedInUser.currentUta' => auth()->user()->getFirstUta(),]);

        $this->get(route('employees.index'))
            ->assertSee(['John Doe', 'Jane Doe'])
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function secretaryShouldListEmployees()
    {
        $this->actingAs(self::$userSec)
            ->withSession(['loggedInUser.currentUta' => auth()->user()->getFirstUta(),]);

        $this->get(route('employees.index'))
            ->assertSee(['John Doe', 'Jane Doe'])
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function ldiShouldntListEmployees()
    {
        $this->actingAs(self::$userLdi)
            ->withSession(['loggedInUser.currentUta' => auth()->user()->getFirstUta(),]);

        $this->get(route('employees.index'))
            ->assertStatus(403);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function coordinatorShouldListEmployees()
    {
        $this->actingAs(self::$userCoord)
            ->withSession(['loggedInUser.currentUta' => auth()->user()->getFirstUta(),]);

        $this->get(route('employees.index'))
            ->assertSee(['John Doe', 'Jane Doe'])
            ->assertStatus(200);
    }


    // ================= See Employee details Tests =================

    /**
     * Guest Shouldnt access employee details page
     *
     * @return void
     * @test
     */
    public function guestShouldntAccessEmployeesDetailsPage()
    {
        $this->get(route('employees.show', 1))
            ->assertRedirect(route('auth.login'));
    }

    /**
     * Authenticated user without permission Shouldnt Access Employees Details Page
     * @return void
     * @test
     */
    public function authenticatedUserWithoutPermissionShouldntAccessEmployeesDetailsPage()
    {
        $this->actingAs(self::$userAlien)
            ->withSession(['loggedInUser.currentUta' => null, 'loggedInUser.currentUtaId' => null]);

        $this->get(route('employees.show', 1))
            ->assertStatus(403);
    }

    /**
     * Admin user Should access employee details page
     *
     * @return void
     * @test
     */
    public function administratorShouldAccessEmployeesDetailsPage()
    {
        $this->actingAs(self::$userAdm)
            ->withSession(['loggedInUser.currentUta' => auth()->user()->getFirstUta(),]);

        $this->get(route('employees.show', 1))
            ->assertSee(['Listar Colaboradores', 'Nome:', 'CPF:', 'Profissão:', 'Contato e endereço'])
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function directorShouldAccessEmployeesDetailsPage()
    {
        $this->actingAs(self::$userDir)
            ->withSession(['loggedInUser.currentUta' => auth()->user()->getFirstUta(),]);

        $this->get(route('employees.show', 1))
            ->assertSee(['Listar Colaboradores', 'Nome:', 'CPF:', 'Profissão:', 'Contato e endereço'])
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function assistantShouldAccessEmployeesDetailsPage()
    {
        $this->actingAs(self::$userAss)
            ->withSession(['loggedInUser.currentUta' => auth()->user()->getFirstUta(),]);

        $this->get(route('employees.show', 1))
            ->assertSee(['Listar Colaboradores', 'Nome:', 'CPF:', 'Profissão:', 'Contato e endereço'])
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function secretaryShouldAccessEmployeesDetailsPage()
    {
        $this->actingAs(self::$userSec)
            ->withSession(['loggedInUser.currentUta' => auth()->user()->getFirstUta(),]);

        $this->get(route('employees.show', 1))
            ->assertSee(['Listar Colaboradores', 'Nome:', 'CPF:', 'Profissão:', 'Contato e endereço'])
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function ldiShouldntAccessEmployeesDetailsPage()
    {
        $this->actingAs(self::$userLdi)
            ->withSession(['loggedInUser.currentUta' => auth()->user()->getFirstUta(),]);

        $this->get(route('employees.show', 1))
            ->assertStatus(403);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function coordinatorShouldntAccessEmployeesDetailsPage()
    {
        $this->actingAs(self::$userCoord)
            ->withSession(['loggedInUser.currentUta' => auth()->user()->getFirstUta(),]);

        $this->get(route('employees.show', 1))
            ->assertStatus(403);
    }


    // ================= See Create Form Tests =================

    /**
     * Guest Shouldnt access create employee page
     *
     * @return void
     * @test
     */
    public function guestShouldntAccessCreateEmployeesPage()
    {
        $this->get(route('employees.create'))
            ->assertRedirect(route('auth.login'));
    }

    /**
     * Authenticated user without permission Shouldnt Access create employee page
     * @return void
     * @test
     */
    public function authenticatedUserWithoutPermissionShouldntAccessCreateEmployeesPage()
    {
        $this->actingAs(self::$userAlien)
            ->withSession(['loggedInUser.currentUta' => null, 'loggedInUser.currentUtaId' => null]);

        $this->get(route('employees.create'))
            ->assertStatus(403);
    }

    /**
     * Admin user Should access create employee page
     *
     * @return void
     * @test
     */
    public function administratorShouldAccessCreateEmployeesPage()
    {
        $this->actingAs(self::$userAdm)
            ->withSession(['loggedInUser.currentUta' => auth()->user()->getFirstUta(),]);

        $this->get(route('employees.create'))
            ->assertSee(['Cadastrar Colaborador', 'Nome*', 'CPF*', 'Profissão', 'Cadastrar'])
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function directorShouldAccessCreateEmployeesPage()
    {
        $this->actingAs(self::$userDir)
            ->withSession(['loggedInUser.currentUta' => auth()->user()->getFirstUta(),]);

        $this->get(route('employees.create'))
            ->assertSee(['Cadastrar Colaborador', 'Nome*', 'CPF*', 'Profissão', 'Cadastrar'])
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function assistantShouldAccessCreateEmployeesPage()
    {
        $this->actingAs(self::$userAss)
            ->withSession(['loggedInUser.currentUta' => auth()->user()->getFirstUta(),]);

        $this->get(route('employees.create'))
            ->assertSee(['Cadastrar Colaborador', 'Nome*', 'CPF*', 'Profissão', 'Cadastrar'])
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function secretaryShouldAccessCreateEmployeesPage()
    {
        $this->actingAs(self::$userSec)
            ->withSession(['loggedInUser.currentUta' => auth()->user()->getFirstUta(),]);

        $this->get(route('employees.create'))
            ->assertSee(['Cadastrar Colaborador', 'Nome*', 'CPF*', 'Profissão', 'Cadastrar'])
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function ldiShouldntAccessCreateEmployeesPage()
    {
        $this->actingAs(self::$userLdi)
            ->withSession(['loggedInUser.currentUta' => auth()->user()->getFirstUta(),]);

        $this->get(route('employees.create'))
            ->assertStatus(403);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function coordinatorShouldntAccessCreateEmployeesPage()
    {
        $this->actingAs(self::$userCoord)
            ->withSession(['loggedInUser.currentUta' => auth()->user()->getFirstUta(),]);

        $this->get(route('employees.create'))
            ->assertStatus(403);
    }


    // ================= Create Employee Tests =================

    /** @return Employee */
    private function createTestEmployee(): Employee
    {
        return Employee::factory()->make(
            [
                'name' => 'Carl Doe',
                'cpf' => $this->faker->unique()->cpf($formatted = false),
                'job' => 'carldoejob',
                'gender_id' => null,
                'birthday' => '',
                'birth_state_id' => null,
                'birth_city' => '',
                'id_number' => '',
                'document_type_id' => null,
                'id_issue_date' => '',
                'id_issue_agency' => '',
                'marital_status_id' => null,
                'spouse_name' => '',
                'father_name' => '',
                'mother_name' => '',
                'address_street' => '',
                'address_complement' => (debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2)[1]['function']),
                'address_number' => '',
                'address_district' => '',
                'address_postal_code' => '',
                'address_state_id' => null,
                'address_city' => '',
                'area_code' => '',
                'phone' => '',
                'mobile' => '',
                'email' => (debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2)[1]['function']) . '@test-case.com',
            ]
        );
    }

    /** @return array  */
    private function expectedEmployeeInfo(): array
    {
        return ['Carl Doe'];
    }

    /**
     * Guest Shouldnt create employee
     * @return void
     * @test
     */
    public function guestShouldntCreateEmployee()
    {
        $employeeArr = $this->createTestEmployee()->toArray();
        Arr::forget($employeeArr, ['id', 'created_at', 'updated_at']);

        $this->post(route('employees.store'), $employeeArr)
            ->assertRedirect(route('auth.login'));
    }

    /**
     * Authenticated user without permission Shouldnt create employee
     * @return void
     * @test
     */
    public function authenticatedUserWithoutPermissionShouldntCreateEmployee()
    {

        $employeeArr = $this->createTestEmployee()->toArray();
        Arr::forget($employeeArr, ['id', 'created_at', 'updated_at']);

        $request = $this->actingAs(self::$userAlien)
            ->withSession(['loggedInUser.currentUta' => null, 'loggedInUser.currentUtaId' => null])
            ->followingRedirects()->post(route('employees.store'), $employeeArr)
            ->assertStatus(403);
    }

    /**
     * Admin user Should create employees
     *
     * @return void
     * @test
     */
    public function administratorShouldCreateEmployee()
    {

        $employeeArr = $this->createTestEmployee()->toArray();
        Arr::forget($employeeArr, ['id', 'created_at', 'updated_at']);

        $request = $this->actingAs(self::$userAdm)
            ->withSession(['loggedInUser.currentUta' => auth()->user()->getFirstUta(),])
            ->followingRedirects()->post(route('employees.store'), $employeeArr)
            ->assertSee($this->expectedEmployeeInfo())
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function directorShouldCreateEmployee()
    {
        $this->actingAs(self::$userDir)
            ->withSession(['loggedInUser.currentUta' => auth()->user()->getFirstUta(),]);

        $employeeArr = $this->createTestEmployee()->toArray();
        Arr::forget($employeeArr, ['id', 'created_at', 'updated_at']);

        $this->followingRedirects()->post(route('employees.store'), $employeeArr)
            ->assertSee($this->expectedEmployeeInfo())
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function assistantShouldCreateEmployee()
    {
        $this->actingAs(self::$userAss)
            ->withSession(['loggedInUser.currentUta' => auth()->user()->getFirstUta(),]);

        $employeeArr = $this->createTestEmployee()->toArray();
        Arr::forget($employeeArr, ['id', 'created_at', 'updated_at']);

        $this->followingRedirects()->post(route('employees.store'), $employeeArr)
            ->assertSee($this->expectedEmployeeInfo())
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function secretaryShouldCreateEmployee()
    {
        $this->actingAs(self::$userSec)
            ->withSession(['loggedInUser.currentUta' => auth()->user()->getFirstUta(),]);

        $employeeArr = $this->createTestEmployee()->toArray();
        Arr::forget($employeeArr, ['id', 'created_at', 'updated_at']);

        $this->followingRedirects()->post(route('employees.store'), $employeeArr)
            ->assertSee($this->expectedEmployeeInfo())
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function coordinatorShouldntCreateEmployee()
    {
        $this->actingAs(self::$userCoord)
            ->withSession(['loggedInUser.currentUta' => auth()->user()->getFirstUta(),]);

        $employeeArr = $this->createTestEmployee()->toArray();
        Arr::forget($employeeArr, ['id', 'created_at', 'updated_at']);

        $this->followingRedirects()->post(route('employees.store'), $employeeArr)
            ->assertStatus(403);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function ldiShouldntCreateEmployee()
    {
        $this->actingAs(self::$userLdi)
            ->withSession(['loggedInUser.currentUta' => auth()->user()->getFirstUta(),]);

        $employeeArr = $this->createTestEmployee()->toArray();
        Arr::forget($employeeArr, ['id', 'created_at', 'updated_at']);

        $this->followingRedirects()->post(route('employees.store'), $employeeArr)
            ->assertStatus(403);
    }


    // ================= See Edit Form Tests =================

    /**
     * Guest Shouldnt access edit employee page
     *
     * @return void
     * @test
     */
    public function guestShouldntAccessEditEmployeesPage()
    {
        $this->get(route('employees.edit', 1))
            ->assertRedirect(route('auth.login'));
    }

    /**
     * Authenticated user without permission Shouldnt access edit employee page
     * @return void
     * @test
     */
    public function authenticatedUserWithoutPermissionShouldntAccessEditEmployeesPage()
    {
        $this->actingAs(self::$userAlien)
            ->withSession(['loggedInUser.currentUta' => null, 'loggedInUser.currentUtaId' => null]);

        $this->get(route('employees.edit', 1))
            ->assertStatus(403);
    }

    /**
     * Admin user Should access edit employee page
     *
     * @return void
     * @test
     */
    public function administratorShouldAccessEditEmployeesPage()
    {
        $this->actingAs(self::$userAdm)
            ->withSession(['loggedInUser.currentUta' => auth()->user()->getFirstUta(),]);

        $this->get(route('employees.edit', 1))
            ->assertSee(['Editar', 'Nome*', 'CPF*', 'Profissão', 'Atualizar'])
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function directorShouldAccessEditEmployeesPage()
    {
        $this->actingAs(self::$userDir)
            ->withSession(['loggedInUser.currentUta' => auth()->user()->getFirstUta(),]);

        $this->get(route('employees.edit', 1))
            ->assertSee(['Editar', 'Nome*', 'CPF*', 'Profissão', 'Atualizar'])
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function assistantShouldAccessEditEmployeesPage()
    {
        $this->actingAs(self::$userAss)
            ->withSession(['loggedInUser.currentUta' => auth()->user()->getFirstUta(),]);

        $this->get(route('employees.edit', 1))
            ->assertSee(['Editar', 'Nome*', 'CPF*', 'Profissão', 'Atualizar'])
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function secretaryShouldAccessEditEmployeesPage()
    {
        $this->actingAs(self::$userSec)
            ->withSession(['loggedInUser.currentUta' => auth()->user()->getFirstUta(),]);

        $this->get(route('employees.edit', 1))
            ->assertSee(['Editar', 'Nome*', 'CPF*', 'Profissão', 'Atualizar'])
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function ldiShouldntAccessEditEmployeesPage()
    {
        $this->actingAs(self::$userLdi)
            ->withSession(['loggedInUser.currentUta' => auth()->user()->getFirstUta(),]);

        $this->get(route('employees.edit', 1))
            ->assertStatus(403);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function coordinatorShouldntAccessEditEmployeesPage()
    {
        $this->actingAs(self::$userCoord)
            ->withSession(['loggedInUser.currentUta' => auth()->user()->getFirstUta(),]);

        $this->get(route('employees.edit', 1))
            ->assertStatus(403);
    }


    // ================= Update Employee Tests =================

    private function updatedEmployeeData(): array
    {
        return [
            'name' => 'John Doe Updated',
            'address_complement' => (debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2)[1]['function']),
            'email' => (debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2)[1]['function']) . '@test-case.com',
        ];
    }

    /**
     * Guest Shouldnt update employee
     * @return void
     * @test
     */
    public function guestShouldntUpdateEmployee()
    {
        $originalEmployee = Employee::where('name', 'John Doe')->first();
        $originalEmployeeArr = $originalEmployee->toArray();
        Arr::forget($originalEmployeeArr, ['id', 'created_at', 'updated_at']);

        $originalEmployeeArr['name'] = $this->updatedEmployeeData()['name'];
        $originalEmployeeArr['address_complement'] = $this->updatedEmployeeData()['address_complement'];
        $originalEmployeeArr['email'] = $this->updatedEmployeeData()['email'];

        $this->put(route('employees.update', $originalEmployee->id), $originalEmployeeArr)
            ->assertRedirect(route('auth.login'));
    }

    /**
     * Authenticated user without permission Shouldnt update employee
     * @return void
     * @test
     */
    public function authenticatedUserWithoutPermissionShouldntUpdateEmployee()
    {
        $this->actingAs(self::$userAlien)
            ->withSession(['loggedInUser.currentUta' => null, 'loggedInUser.currentUtaId' => null]);

        $originalEmployee = Employee::where('name', 'John Doe')->first();
        $originalEmployeeArr = $originalEmployee->toArray();
        Arr::forget($originalEmployeeArr, ['id', 'created_at', 'updated_at']);

        $originalEmployeeArr['name'] = $this->updatedEmployeeData()['name'];
        $originalEmployeeArr['address_complement'] = $this->updatedEmployeeData()['address_complement'];
        $originalEmployeeArr['email'] = $this->updatedEmployeeData()['email'];

        $this->put(route('employees.update', $originalEmployee->id), $originalEmployeeArr)
            ->assertStatus(403);
    }

    /**
     * Admin user Should update employee
     *
     * @return void
     * @test
     */
    public function administratorShouldUpdateEmployee()
    {
        $this->actingAs(self::$userAdm)
            ->withSession(['loggedInUser.currentUta' => auth()->user()->getFirstUta(),]);

        $originalEmployee = Employee::where('name', 'John Doe')->first();
        $originalEmployeeArr = $originalEmployee->toArray();
        Arr::forget($originalEmployeeArr, ['id', 'created_at', 'updated_at']);

        $originalEmployeeArr['name'] = $this->updatedEmployeeData()['name'];
        $originalEmployeeArr['address_complement'] = $this->updatedEmployeeData()['address_complement'];
        $originalEmployeeArr['email'] = $this->updatedEmployeeData()['email'];

        $this->followingRedirects()->put(route('employees.update', $originalEmployee->id), $originalEmployeeArr)
            ->assertSee($this->updatedEmployeeData())
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function directorShouldUpdateEmployee()
    {
        $this->actingAs(self::$userDir)
            ->withSession(['loggedInUser.currentUta' => auth()->user()->getFirstUta(),]);

        $originalEmployee = Employee::where('name', 'John Doe')->first();
        $originalEmployeeArr = $originalEmployee->toArray();
        Arr::forget($originalEmployeeArr, ['id', 'created_at', 'updated_at']);

        $originalEmployeeArr['name'] = $this->updatedEmployeeData()['name'];
        $originalEmployeeArr['address_complement'] = $this->updatedEmployeeData()['address_complement'];
        $originalEmployeeArr['email'] = $this->updatedEmployeeData()['email'];

        $this->followingRedirects()->put(route('employees.update', $originalEmployee->id), $originalEmployeeArr)
            ->assertSee($this->updatedEmployeeData())
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function assistantShouldUpdateEmployee()
    {
        $this->actingAs(self::$userAss)
            ->withSession(['loggedInUser.currentUta' => auth()->user()->getFirstUta(),]);

        $originalEmployee = Employee::where('name', 'John Doe')->first();
        $originalEmployeeArr = $originalEmployee->toArray();
        Arr::forget($originalEmployeeArr, ['id', 'created_at', 'updated_at']);

        $originalEmployeeArr['name'] = $this->updatedEmployeeData()['name'];
        $originalEmployeeArr['address_complement'] = $this->updatedEmployeeData()['address_complement'];
        $originalEmployeeArr['email'] = $this->updatedEmployeeData()['email'];

        $this->followingRedirects()->put(route('employees.update', $originalEmployee->id), $originalEmployeeArr)
            ->assertSee($this->updatedEmployeeData())
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function secretaryShouldUpdateEmployee()
    {
        $this->actingAs(self::$userSec)
            ->withSession(['loggedInUser.currentUta' => auth()->user()->getFirstUta(),]);

        $originalEmployee = Employee::where('name', 'John Doe')->first();
        $originalEmployeeArr = $originalEmployee->toArray();
        Arr::forget($originalEmployeeArr, ['id', 'created_at', 'updated_at']);

        $originalEmployeeArr['name'] = $this->updatedEmployeeData()['name'];
        $originalEmployeeArr['address_complement'] = $this->updatedEmployeeData()['address_complement'];
        $originalEmployeeArr['email'] = $this->updatedEmployeeData()['email'];

        $this->followingRedirects()->put(route('employees.update', $originalEmployee->id), $originalEmployeeArr)
            ->assertSee($this->updatedEmployeeData())
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function coordinatorShouldntUpdateEmployee()
    {
        $this->actingAs(self::$userCoord)
            ->withSession(['loggedInUser.currentUta' => auth()->user()->getFirstUta(),]);

        $originalEmployee = Employee::where('name', 'John Doe')->first();
        $originalEmployeeArr = $originalEmployee->toArray();
        Arr::forget($originalEmployeeArr, ['id', 'created_at', 'updated_at']);

        $originalEmployeeArr['name'] = $this->updatedEmployeeData()['name'];
        $originalEmployeeArr['address_complement'] = $this->updatedEmployeeData()['address_complement'];
        $originalEmployeeArr['email'] = $this->updatedEmployeeData()['email'];

        $this->put(route('employees.update', $originalEmployee->id), $originalEmployeeArr)
            ->assertStatus(403);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function ldiShouldntUpdateEmployee()
    {
        $this->actingAs(self::$userLdi)
            ->withSession(['loggedInUser.currentUta' => auth()->user()->getFirstUta(),]);

        $originalEmployee = Employee::where('name', 'John Doe')->first();
        $originalEmployeeArr = $originalEmployee->toArray();
        Arr::forget($originalEmployeeArr, ['id', 'created_at', 'updated_at']);

        $originalEmployeeArr['name'] = $this->updatedEmployeeData()['name'];
        $originalEmployeeArr['address_complement'] = $this->updatedEmployeeData()['address_complement'];
        $originalEmployeeArr['email'] = $this->updatedEmployeeData()['email'];

        $this->put(route('employees.update', $originalEmployee->id), $originalEmployeeArr)
            ->assertStatus(403);
    }


    // ================= Delete Employee Tests =================

    /**
     * Guest Shouldnt delete employee
     *
     * @return void
     * @test
     */
    public function guestShouldntDeleteEmployees()
    {
        $employeeBefore = Employee::find(1);

        $this->delete(route('employees.destroy', 1))
            ->assertRedirect(route('auth.login'));

        $employeeAfter = Employee::find(1);
        $this->assertEquals($employeeBefore, $employeeAfter);
    }

    /**
     * Authenticated user without permission Shouldnt delete employee
     * @return void
     * @test
     */
    public function authenticatedUserWithoutPermissionShouldntDeleteEmployees()
    {
        $this->actingAs(self::$userAlien)
            ->withSession(['loggedInUser.currentUta' => null, 'loggedInUser.currentUtaId' => null]);

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
     * @test
     */
    public function administratorShouldDeleteEmployees()
    {
        $this->actingAs(self::$userAdm)
            ->withSession(['loggedInUser.currentUta' => auth()->user()->getFirstUta(),]);

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
     * @test
     */
    public function directorShouldntDeleteEmployees()
    {
        $this->actingAs(self::$userDir)
            ->withSession(['loggedInUser.currentUta' => auth()->user()->getFirstUta(),]);

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
     * @test
     */
    public function assistantShouldntDeleteEmployees()
    {
        $this->actingAs(self::$userAss)
            ->withSession(['loggedInUser.currentUta' => auth()->user()->getFirstUta(),]);

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
     * @test
     */
    public function secretaryShouldntDeleteEmployees()
    {
        $this->actingAs(self::$userSec)
            ->withSession(['loggedInUser.currentUta' => auth()->user()->getFirstUta(),]);

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
     * @test
     */
    public function ldiShouldntDeleteEmployees()
    {
        $this->actingAs(self::$userLdi)
            ->withSession(['loggedInUser.currentUta' => auth()->user()->getFirstUta(),]);

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
     * @test
     */
    public function coordinatorShouldntDeleteEmployees()
    {
        $this->actingAs(self::$userCoord)
            ->withSession(['loggedInUser.currentUta' => auth()->user()->getFirstUta(),]);

        $employeeBefore = Employee::find(1);

        $this->delete(route('employees.destroy', 1))
            ->assertStatus(403);

        $employeeAfter = Employee::find(1);
        $this->assertEquals($employeeBefore, $employeeAfter);
    }

    //
    // TODO: move mock user functions to factory or helper
    //
}
