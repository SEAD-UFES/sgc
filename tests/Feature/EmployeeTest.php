<?php

namespace Tests\Feature;

use App\Models\Employee;
use App\Models\User;
use App\Models\UserType;
use App\Models\UserTypeAssignment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmployeeTest extends TestCase
{
    use RefreshDatabase;

    protected $employeeData = [
        'name' => 'Fulano de Tal',
        'cpf' => '12345678900',
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

    protected $newEmployeeData = [
        'name' => 'updated',
        'cpf' => '69696969696',
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

    /**
     * Guest cannot list employees
     * @return void
     */
    public function testGuestCannotListEmployees()
    {
        $this->get(route('employees.index'))
            ->assertRedirect(route('auth.login'));
    }

    /**
     * Guest cannot create an employee
     * @return void
     */
    public function testGuestCannotCreateEmployee()
    {
        $this->post(route('employees.store'), $this->employeeData)
            ->assertRedirect(route('auth.login'));
    }

    /**
     * Guest cannot update an employee
     * @return void
     */
    public function testGuestCannotUpdateEmployee()
    {
        $employee = $this->getTestEmployee();

        $this->put(route('employees.update', $employee->id), $this->employeeData)
            ->assertRedirect(route('auth.login'));
    }

    /**
     * Guest cannot delete an employee
     * @return void
     */
    public function testGuestCannotDeleteEmployee()
    {
        $employee = $this->getTestEmployee();

        $this->get(route('employees.destroy', $employee->id))
            ->assertRedirect(route('auth.login'));
    }

    /**
     * Guest cannot access create employee page
     * @return void
     */
    public function testGuestCannotAccessCreateEmployeePage()
    {
        $this->get(route('employees.create'))
            ->assertRedirect(route('auth.login'));
    }

    /**
     * Guest cannot access edit employee page
     * @return void
     */
    public function testGuestCannotAccessEditEmployeePage()
    {
        $employee = $this->getTestEmployee();

        $this->get(route('employees.edit', $employee->id))
            ->assertRedirect(route('auth.login'));
    }

    /**
     * Guest cannot access employee details page
     * @return void
     */
    public function testGuestCannotAccessEmployeeDetails()
    {
        $employee = $this->getTestEmployee();

        $this->get(route('employees.show', $employee->id))
            ->assertRedirect(route('auth.login'));
    }

    /**
     * Authenticated user cannot list employees
     * @return void
     */
    public function testAuthenticatedUserWithoutPermissionAssignmentCannotListEmployees()
    {
        $session = $this->getAuthenticatedSession();

        $session->get(route('employees.index'))
            ->assertSee('Acesso negado');
    }

    /**
     * Authenticated user cannot create employee
     * @return void
     */
    public function testAuthenticatedUserWithoutPermissionAssignmentCannotCreateEmployee()
    {
        $session = $this->getAuthenticatedSession();

        $session->post(route('employees.store'), $this->employeeData)
            ->assertSee('Acesso negado');
    }

    /**
     * Authenticated user cannot update employee
     * @return void
     */
    public function testAuthenticatedUserWithoutPermissionAssignmentCannotUpdateEmployee()
    {
        $session = $this->getAuthenticatedSession();

        $employee = $this->getTestEmployee();

        $session->put(route('employees.update', $employee->id), $this->newEmployeeData)
            ->assertSee('Acesso negado');
    }

    /**
     * Authenticated user cannot delete employee
     * @return void
     */
    public function testAuthenticatedUserWithoutPermissionAssignmentCannotDeleteEmployee()
    {
        $session = $this->getAuthenticatedSession();

        $employee = $this->getTestEmployee();

        $this->assertEquals(Employee::count(), 1);

        $session
            ->delete(route('employees.destroy', $employee->id))
            ->assertSee('Acesso negado');
    }

    /**
     * Authenticated user cannot access employee create page
     * @return void
     */
    public function testAuthenticatedUserWithoutPermissionAssignmentCannotAccessCreateEmployeePage()
    {
        $session = $this->getAuthenticatedSession();

        $session
            ->get(route('employees.create'))
            ->assertSee('Acesso negado');
    }

    /**
     * Authenticated user cannot access employee edit page
     * @return void
     */
    public function testAuthenticatedUserWithoutPermissionAssignmentCannotAccessEmployeeEditPage()
    {
        $session = $this->getAuthenticatedSession();

        $employee = $this->getTestEmployee();

        $session
            ->get(route('employees.edit', $employee->id))
            ->assertSee('Acesso negado');
    }

    /**
     * Authenticated user cannot access employee details page
     * @return void
     */
    public function testAuthenticatedUserWithoutPermissionAssignmentCannotAccessEmployeeDetailsPage()
    {
        $session = $this->getAuthenticatedSession();

        $employee = $this->getTestEmployee();

        $session->get(route('employees.show', $employee->id))
            ->assertSee('Acesso negado');
    }

    /**
     * Admin user can list employees
     * @return void
     */
    public function testAdminUserCanListEmployees()
    {
        $session = $this->getAdminUser();

        // empty state
        $session->get(route('employees.index'))
            ->assertSee('Listar Colaboradores');

        // populated
        Employee::factory()->count(20)->create();

        $session->get(route('employees.index'))
            ->assertSee('pagination');
    }

    /**
     * Admin user can create employees
     * @return void
     */
    public function testAdminUserCanCreateEmployee()
    {
        $session = $this->getAdminUser();

        $this->assertEquals(Employee::count(), 0);

        $session->post(route('employees.store'), $this->employeeData)
            ->assertStatus(302);

        $this->assertEquals(Employee::count(), 1);

        $employee = Employee::first();

        $this->assertEquals($employee->name, $this->employeeData["name"]);
        $this->assertEquals($employee->cpf, $this->employeeData["cpf"]);
        $this->assertEquals($employee->email, $this->employeeData["email"]);
    }

    /**
     * Admin user can update employee
     * @return void
     */
    public function testAdminUserCanUpdateEmployee()
    {
        $session = $this->getAdminUser();

        $employee = $this->getTestEmployee();

        $session->put(route('employees.update', $employee->id), $this->newEmployeeData)->assertStatus(302);

        $updatedEmployee = Employee::find($employee->id);

        $this->assertEquals($updatedEmployee->name, "updated");
        $this->assertEquals($updatedEmployee->email, "updated@email.com");
    }

    /**
     * Admin user can delete employee
     * @return void
     */
    public function testAdminUserCanDeleteEmployee()
    {
        $session = $this->getAdminUser();

        $employee = $this->getTestEmployee();

        $this->assertEquals(Employee::count(), 1);

        $session
            ->delete(route('employees.destroy', $employee->id))
            ->assertStatus(302);

        $this->assertEquals(Employee::count(), 0);
    }

    /**
     * Admin user can access create employee page
     * @return void
     */
    public function testAdminUserCanAccessCreateEmployeePage()
    {
        $session = $this->getAdminUser();

        $session
            ->get(route('employees.create'))
            ->assertOk();
    }

    /**
     * Admin user can access edit employee page
     * @return void
     */
    public function testAdminUserCanAccessEditEmployeePage()
    {
        $session = $this->getAdminUser();

        $employee = $this->getTestEmployee();

        $session
            ->get(route('employees.edit', $employee->id))
            ->assertSee($employee->name)
            ->assertSee($employee->cpf)
            ->assertSee($employee->email);
    }

    /**
     * Admin user can access employee details page
     * @return void
     */
    public function testAdminUserCanAccessEmployeeDetailsPage()
    {
        $session = $this->getAdminUser();

        $employee = $this->getTestEmployee();

        $session->get(route('employees.show', $employee->id))
            ->assertSee($employee->name)
            ->assertSee($employee->cpf)
            ->assertSee($employee->email);
    }

    //
    // TODO: move mock user functions to factory or helper
    //

    /**
     * Get User with Admin profile and Session
     * @return \App\Models\User
     */
    private function getAdminUser()
    {
        $user = User::factory()->create(["employee_id" => null]);

        $adminType = UserType::factory()->create(['acronym' => 'adm']);

        $assignment = UserTypeAssignment::factory()->create([
            'user_id' => $user->id,
            'user_type_id' => $adminType->id,
            'course_id' => null,
        ]);

        $session = $this->actingAs($user)
            ->withSession(['current_uta' => auth()->user()->getFirstUTA(), 'current_uta_id' => auth()->user()->getFirstUTA()->id]);

        return $session;
    }

    /**
     * Get user with SessionUser attached
     * @return \App\Models\User
     */
    private function getAuthenticatedSession()
    {
        $user = User::factory()->create(["employee_id" => null]);

        $session = $this->actingAs($user)
            ->withSession(['current_uta_id' => null, 'current_uta_id' => null]);

        return $session;
    }

    /**
     * Get Employee for testing (pessisted in database)
     * @return \App\Models\Employee
     */
    private function getTestEmployee()
    {
        $employee = Employee::factory()->create();

        return $employee;
    }
}
