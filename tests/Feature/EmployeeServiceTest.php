<?php

namespace Tests\Feature;

use App\Models\Bond;
use App\Models\BondDocument;
use App\Models\Employee;
use App\Models\User;
use App\Services\EmployeeService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class EmployeeServiceTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * @var EmployeeService
     */
    private EmployeeService $service;

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

        $this->service = new EmployeeService();
    }

    /**
     * @test
     */
    public function employeesShouldBeListed(): void
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
    public function employeeShouldBeRetrieved(): void
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
    public function employeeShouldBeCreated(): void
    {
        //setting up scenario
        $attributes = [];

        $attributes['name'] = 'Mary Doe';
        $attributes['cpf'] = '33333333333';
        $attributes['email'] = 'marydoe@test3.com';
        $attributes = array_merge($attributes, $this->getBankAccountAttributes());

        Event::fakeFor(function () use ($attributes) {
            //execution
            $this->service->create($attributes);

            //verifications
            Event::assertDispatched('eloquent.created: ' . Employee::class);
            $this->assertEquals('Mary Doe', Employee::find(3)?->name);
            $this->assertCount(3, Employee::all());
        });
    }

    /**
     * @test
     */
    public function employeeShouldBeCreatedWithUser(): void
    {
        //setting up scenario
        $attributes = [];

        $attributes['name'] = 'Mary Doe';
        $attributes['cpf'] = '33333333333';
        $attributes['email'] = 'marydoe@test3.com';
        $attributes = array_merge($attributes, $this->getBankAccountAttributes());

        User::factory()->create(['email' => 'marydoe@test3.com', 'employee_id' => null]);

        Event::fakeFor(function () use ($attributes) {
            //execution
            $this->service->create($attributes);

            //verifications
            Event::assertDispatched('eloquent.created: ' . Employee::class);
            $this->assertEquals('Mary Doe', Employee::find(3)?->name);
            $this->assertEquals('marydoe@test3.com', Employee::find(3)?->user?->email);
            $this->assertCount(3, Employee::all());
        });
    }

    /**
     * @test
     */
    public function employeeShouldBeUpdated(): void
    {
        //setting up scenario

        $employee = Employee::find(1);

        $attributes = [];

        $attributes['name'] = 'Bob Doe';
        $attributes['cpf'] = '44444444444';
        $attributes['email'] = 'bobdoe@test4.com';
        $attributes = array_merge($attributes, $this->getBankAccountAttributes());

        Event::fakeFor(function () use ($employee, $attributes) {
            //execution
            $this->service->update($attributes, $employee);

            //verifications
            Event::assertDispatched('eloquent.updated: ' . Employee::class);
            $this->assertEquals('Bob Doe', Employee::find(1)?->name);
            $this->assertEquals('bobdoe@test4.com', Employee::find(1)?->email);
            $this->assertCount(2, Employee::all());
        });
    }

    /**
     * @test
     */
    public function employeeShouldBeDeleted(): void
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
    public function employeeWithUserShouldBeDeleted(): void
    {
        //setting up scenario
        $employee = Employee::find(1);

        $user = User::factory()->createOne(['email' => 'marydoe@test3.com', 'employee_id' => null]);
        $user->employee_id = $employee?->id;
        $user->save();

        $user = User::find(1);

        Event::fakeFor(function () use ($employee) {
            //execution
            $this->service->delete($employee);

            //verifications
            Event::assertDispatched('eloquent.deleted: ' . Employee::class);
            $this->assertEquals('Jane Doe', $this->service->list()->first()->name);
            $this->assertNull(User::find(1)?->employee_id);
            $this->assertCount(1, Employee::all());
        });
    }

    /**
     * @test
     */
    public function employeeWithBondDocumentsShouldBeDeleted(): void
    {
        //setting up scenario
        $employee = Employee::find(1);

        /**
         * @var Bond $bond
         */
        $bond = Bond::factory()->createOne([
            'employee_id' => $employee?->id,
        ]);

        BondDocument::factory()->createOne([
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
    

    /**
     * @return array<string, string>
     */
    private function getBankAccountAttributes(): array
    {
        $generator = $this->faker->unique();

        return [
            'bank_name' => 'Test Bank',
            'agency_number' => (string) $generator->numberBetween(1000, 9999),
            'account_number' => (string) $generator->numberBetween(1000, 9999),
        ];
    }
}
