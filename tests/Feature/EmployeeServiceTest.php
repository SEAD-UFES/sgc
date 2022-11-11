<?php

namespace Tests\Feature;

use App\Events\ModelListed;
use App\Events\ModelRead;
use App\Models\Bond;
use App\Models\Document;
use App\Models\Employee;
use App\Models\User;
use App\Services\Dto\EmployeeDto;
use App\Services\Dto\EmployeeDto;
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
            Event::assertDispatched(ModelListed::class);
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
            Event::assertDispatched(ModelRead::class);
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
        $attributes['job'] = '';
        $attributes['birth_date'] = '';
        $attributes['birthCity'] = '';
        $attributes['idNumber'] = '';
        $attributes['idIssueDate'] = '';
        $attributes['idIssueAgency'] = '';
        $attributes['spouseName'] = '';
        $attributes['fatherName'] = '';
        $attributes['motherName'] = '';
        $attributes['addressStreet'] = '';
        $attributes['addressComplement'] = '';
        $attributes['addressNumber'] = '';
        $attributes['addressDistrict'] = '';
        $attributes['addressPostalCode'] = '';
        $attributes['addressCity'] = '';
        $attributes['areaCode'] = '';
        $attributes['phone'] = '';
        $attributes['mobile'] = '';
        $attributes['email'] = 'marydoe@test3.com';

        $attributes = array_merge($attributes, $this->getBankAccountAttributes());

        $dto = new EmployeeDto($attributes);

        Event::fakeFor(function () use ($dto) {
            //execution
            $this->service->create($dto);

            //verifications
            Event::assertDispatched('eloquent.created: ' . Employee::class);
            $this->assertEquals('Mary Doe', Employee::find(3)?->name);
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
        $attributes['job'] = '';
        $attributes['birth_date'] = '';
        $attributes['birthCity'] = '';
        $attributes['idNumber'] = '';
        $attributes['idIssueDate'] = '';
        $attributes['idIssueAgency'] = '';
        $attributes['spouseName'] = '';
        $attributes['fatherName'] = '';
        $attributes['motherName'] = '';
        $attributes['addressStreet'] = '';
        $attributes['addressComplement'] = '';
        $attributes['addressNumber'] = '';
        $attributes['addressDistrict'] = '';
        $attributes['addressPostalCode'] = '';
        $attributes['addressCity'] = '';
        $attributes['areaCode'] = '';
        $attributes['phone'] = '';
        $attributes['mobile'] = '';
        $attributes['email'] = 'bobdoe@test4.com';
        
        $attributes = array_merge($attributes, $this->getBankAccountAttributes());

        $dto = new EmployeeDto($attributes);

        Event::fakeFor(function () use ($employee, $dto) {
            //execution
            $this->service->update($dto, $employee);

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
    public function employeeWithDocumentsShouldBeDeleted(): void
    {
        //setting up scenario
        $employee = Employee::find(1);

        /**
         * @var Bond $bond
         */
        $bond = Bond::factory()->createOne([
            'employee_id' => $employee?->id,
        ]);

        Document::factory()->createOne([
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
            'bankName' => 'Test Bank',
            'agencyNumber' => (string) $generator->numberBetween(1000, 9999),
            'accountNumber' => (string) $generator->numberBetween(1000, 9999),
        ];
    }
}
