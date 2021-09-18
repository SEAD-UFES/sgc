<?php

namespace Tests\Feature;

use App\CustomClasses\SessionUser;
use App\Models\Employee;
use App\Models\User;
use App\Models\UserType;
use App\Models\UserTypeAssignment;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class EmployeeTest extends TestCase
{
    use DatabaseMigrations;

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
    public function test_guest_cannot_list_employees()
    {
        $this->get(route('employees.index'))
            ->assertRedirect(route('auth.login'));
    }

    /**
     * Guest cannot create an employee
     * @return void
     */
    public function test_guest_cannot_create_employee()
    {
        $this->post(route('employees.store'), $this->employeeData)
            ->assertRedirect(route('auth.login'));
    }

    /**
     * Guest cannot update an employee
     * @return void
     */
    public function test_guest_cannot_update_employee()
    {
        $employee = $this->getTestEmployee();

        $this->put(route('employees.update', $employee->id), $this->employeeData)
            ->assertRedirect(route('auth.login'));
    }

    /**
     * Guest cannot delete an employee
     * @return void
     */
    public function test_guest_cannot_delete_employee()
    {
        $employee = $this->getTestEmployee();

        $this->get(route('employees.destroy', $employee->id))
            ->assertRedirect(route('auth.login'));
    }

    /**
     * Guest cannot access create employee page
     * @return void
     */
    public function test_guest_cannot_access_create_employee_page()
    {
        $this->get(route('employees.create'))
            ->assertRedirect(route('auth.login'));
    }

    /**
     * Guest cannot access edit employee page
     * @return void
     */
    public function test_guest_cannot_access_edit_employee_page()
    {
        $employee = $this->getTestEmployee();

        $this->get(route('employees.edit', $employee->id))
            ->assertRedirect(route('auth.login'));
    }

    /**
     * Guest cannot access employee details page
     * @return void
     */
    public function test_guest_cannot_access_employee_details()
    {
        $employee = $this->getTestEmployee();

        $this->get(route('employees.show', $employee->id))
            ->assertRedirect(route('auth.login'));
    }

    /**
     * Authenticated user cannot list employees
     * @return void
     */
    public function test_authenticated_user_without_permission_assignment_cannot_list_employees()
    {
        $session = $this->getAuthenticatedSession();

        $session->get(route('employees.index'))
            ->assertSee('Acesso negado');
    }

    /**
     * Authenticated user cannot create employee
     * @return void
     */
    public function test_authenticated_user_without_permission_assignment_cannot_create_employee()
    {
        $session = $this->getAuthenticatedSession();

        $session->post(route('employees.store'), $this->employeeData)
            ->assertSee('Acesso negado');
    }

    /**
     * Authenticated user cannot update employee
     * @return void
     */
    public function test_authenticated_user_without_permission_assignment_cannot_update_employee()
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
    public function test_authenticated_user_without_permission_assignment_cannot_delete_employee()
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
    public function test_authenticated_user_without_permission_assignment_cannot_access_create_employee_page()
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
    public function test_authenticated_user_without_permission_assignment_cannot_access_employee_edit_page()
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
    public function test_authenticated_user_without_permission_assignment_cannot_access_employee_details_page()
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
    public function test_admin_user_can_list_employees()
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
    public function test_admin_user_can_create_employee()
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
    public function test_admin_user_can_update_employee()
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
    public function test_admin_user_can_delete_employee()
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
    public function test_admin_user_can_access_create_employee_page()
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
    public function test_admin_user_can_access_edit_employee_page()
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
    public function test_admin_user_can_access_employee_details_page()
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
            ->withSession(['sessionUser' => new SessionUser($user)]);

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
            ->withSession(['sessionUser' => new SessionUser($user)]);

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
