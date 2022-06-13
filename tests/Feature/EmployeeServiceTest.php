<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Bond;
use App\Models\User;
use App\Models\Employee;
use App\Models\BondDocument;
use App\Services\EmployeeService;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EmployeeServiceTest extends TestCase
{
    use RefreshDatabase;

    //setting up scenario for all tests
    public function setUp(): void
    {
        parent::setUp();

        Employee::factory()->create(
            [
                'name' => 'John Doe',
                'cpf' => '11111111111',
                'email' => 'jonhdoe@test1.com',
            ]
        );

        Employee::factory()->create(
            [
                'name' => 'Jane Doe',
                'cpf' => '22222222222',
                'email' => 'janedoe@test2.com',
            ]
        );

        $this->service = new EmployeeService;
    }

    /**
     * @test
     */
    public function employeesShouldBeListed()
    {
        Event::fakeFor(function () {
            //execution
            $employees = $this->service->list();

            //verifications
            Event::assertDispatched('eloquent.listed: ' . Employee::class);
            $this->assertContains('John Doe', $employees->pluck('name')->toArray());
            $this->assertContains('Jane Doe', $employees->pluck('name')->toArray());
            $this->assertCount(2, $employees);
        });
    }

    /**
     * @test
     */
    public function employeeShouldBeRetrieved()
    {
        //setting up scenario
        $employee = Employee::find(1);

        Event::fakeFor(function () use ($employee) {
            //execution
            $employee = $this->service->read($employee);

            //verifications
            Event::assertDispatched('eloquent.fetched: ' . Employee::class);
            $this->assertEquals('John Doe', $employee->name);
            $this->assertCount(2, Employee::all());
        });
    }

    /**
     * @test
     */
    public function employeeShouldBeCreated()
    {
        //setting up scenario
        $attributes = [];

        $attributes['name'] = 'Mary Doe';
        $attributes['cpf'] = '33333333333';
        $attributes['email'] = 'marydoe@test3.com';

        Event::fakeFor(function () use ($attributes) {
            //execution
            $this->service->create($attributes);

            //verifications
            Event::assertDispatched('eloquent.created: ' . Employee::class);
            $this->assertEquals('Mary Doe', Employee::find(3)->name);
            $this->assertCount(3, Employee::all());
        });
    }

    /**
     * @test
     */
    public function employeeShouldBeCreatedWithUser()
    {
        //setting up scenario
        $attributes = [];

        $attributes['name'] = 'Mary Doe';
        $attributes['cpf'] = '33333333333';
        $attributes['email'] = 'marydoe@test3.com';

        User::factory()->create(['email' => 'marydoe@test3.com', 'employee_id' => null]);

        Event::fakeFor(function () use ($attributes) {
            //execution
            $this->service->create($attributes);

            //verifications
            Event::assertDispatched('eloquent.created: ' . Employee::class);
            $this->assertEquals('Mary Doe', Employee::find(3)->name);
            $this->assertEquals('marydoe@test3.com', Employee::find(3)->user->email);
            $this->assertCount(3, Employee::all());
        });
    }

    /**
     * @test
     */
    public function employeeShouldBeUpdated()
    {
        //setting up scenario

        $employee = Employee::find(1);

        $attributes = [];

        $attributes['name'] = 'Bob Doe';
        $attributes['cpf'] = '44444444444';
        $attributes['email'] = 'bobdoe@test4.com';

        Event::fakeFor(function () use ($employee, $attributes) {
            //execution
            $this->service->update($attributes, $employee);

            //verifications
            Event::assertDispatched('eloquent.updated: ' . Employee::class);
            $this->assertEquals('Bob Doe', Employee::find(1)->name);
            $this->assertEquals('bobdoe@test4.com', Employee::find(1)->email);
            $this->assertCount(2, Employee::all());
        });
    }

    /**
     * @test
     */
    public function employeeShouldBeDeleted()
    {
        //setting up scenario
        $employee = Employee::find(1);

        Event::fakeFor(function () use ($employee) {
            //execution
            $this->service->delete($employee);

            //verifications
            Event::assertDispatched('eloquent.deleted: ' . Employee::class);
            $this->assertEquals('Jane Doe', $this->service->list()->first()->name);
            $this->assertCount(1, Employee::all());
        });
    }

    /**
     * @test
     */
    public function employeeWithUserShouldBeDeleted()
    {
        //setting up scenario
        $employee = Employee::find(1);

        $user = User::factory()->create(['email' => 'marydoe@test3.com', 'employee_id' => null]);
        $user->employee_id = $employee->id;
        $user->save();

        $user = User::find(1);

        Event::fakeFor(function () use ($employee, $user) {
            //execution
            $this->service->delete($employee);

            //verifications
            Event::assertDispatched('eloquent.deleted: ' . Employee::class);
            $this->assertEquals('Jane Doe', $this->service->list()->first()->name);
            $this->assertNull(User::find(1)->employee_id);
            $this->assertCount(1, Employee::all());
        });
    }

    /**
     * @test
     */
    public function employeeWithBondDocumentsShouldBeDeleted()
    {
        //setting up scenario
        $employee = Employee::find(1);

        $bond = Bond::factory()->create([
            'employee_id' => $employee->id,
        ]);

        BondDocument::factory()->create([
            'bond_id' => $bond->id,
        ]);

        Event::fakeFor(function () use ($employee) {
            //execution
            $this->service->delete($employee);

            //verifications
            Event::assertDispatched('eloquent.deleted: ' . Employee::class);
            $this->assertEquals('Jane Doe', $this->service->list()->first()->name);
            $this->assertCount(1, Employee::all());
        });
    }
}
