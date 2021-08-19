<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

use App\Models\User;
use App\Models\Employee;

use App\CustomClasses\SessionUser;

class EmployeeTest extends TestCase
{
    use DatabaseMigrations;

    protected $employeeData = [
        "name"  => "Fulano de Tal",
        "cpf"   => "12345678900",
        "email" => "fulanodetal@mail.com"
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
     * Authenticated user can list employees
     * @return void
     */
    public function test_authenticated_user_can_list_employees()
    {
        $session = $this->getAuthenticatedSession();

        // empty state
        $session->get(route('employees.index'))
            ->assertSee('Listar Colaboradores');

        // populated
        Employee::factory()->count(20)->create();

        $session->get(route('employees.index'))
            ->assertSee('pagination');
    }

    /**
     * Authenticated user can create employees
     * @return void
     */
    public function test_authenticated_user_can_create_employee()
    {
        $session = $this->getAuthenticatedSession();

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
     * Authenticated user can update employee
     * @return void
     */
    public function test_authenticated_user_can_update_employee()
    {
        $session = $this->getAuthenticatedSession();

        $employee = $this->getTestEmployee();

        $employee->name = "updated";
        $employee->email = "updated@mail.com";

        $payload = $employee->toArray();

        $session->put(route('employees.update', $employee->id), $payload)->assertStatus(302);

        $updatedEmployee = Employee::find($employee->id);

        $this->assertEquals($updatedEmployee->name, "updated");
        $this->assertEquals($updatedEmployee->email, "updated@mail.com");
    }


    /**
     * Authenticated user can delete employee
     * @return void
     */
    public function test_authenticated_user_can_delete_employee()
    {
        $session = $this->getAuthenticatedSession();

        $employee = $this->getTestEmployee();

        $this->assertEquals(Employee::count(), 1);

        $session
            ->delete(route('employees.destroy', $employee->id))
            ->assertStatus(302);

        $this->assertEquals(Employee::count(), 0);
    }

    /**
     * Authenticated user can access create employee page
     * @return void
     */
    public function test_authenticated_user_can_access_create_employee_page()
    {
        $session = $this->getAuthenticatedSession();

        $session
            ->get(route('employees.create'))
            ->assertOk();
    }


    /**
     * Authenticated user can access edit employee page
     * @return void
     */
    public function test_authenticated_user_can_access_edit_employee_page()
    {
        $session = $this->getAuthenticatedSession();

        $employee = $this->getTestEmployee();

        $session
            ->get(route('employees.edit', $employee->id))
            ->assertSee($employee->name)
            ->assertSee($employee->cpf)
            ->assertSee($employee->email);
    }


    /**
     * Authenticated user can access employee details page
     * @return void
     */
    public function test_authenticated_user_can_access_employee_details_page()
    {
        $session = $this->getAuthenticatedSession();

        $employee = $this->getTestEmployee();

        $session->get(route('employees.show', $employee->id))
            ->assertSee($employee->name)
            ->assertSee($employee->cpf)
            ->assertSee($employee->email);
    }

    /**
     * Get User with UserSession attached 
     * @return \App\Models\User
     */
    private function getAuthenticatedSession()
    {
        $user = User::factory()->make(["employee_id" => null]);

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
