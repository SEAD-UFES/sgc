<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Bond;
use App\Models\BondDocument;
use App\Models\User;
use App\Models\Employee;
use App\Services\EmployeeService;
use Illuminate\Support\Facades\Log;
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
        //verifications
        $this->assertEquals('John Doe', $this->service->list()->first()->name);
        $this->assertEquals(2, $this->service->list()->count());
    }

    /**
     * @test
     */
    public function employeeShouldBeCreated()
    {
        //setting up scenario
        $attributes = array();

        $attributes['name'] = 'Mary Doe';
        $attributes['cpf'] = '33333333333';
        $attributes['email'] = 'marydoe@test3.com';

        //execution 
        $this->service->create($attributes);

        //verifications
        $this->assertEquals('Mary Doe', Employee::find(3)->name);
        $this->assertEquals(3, Employee::all()->count());
    }

    /**
     * @test
     */
    public function employeeShouldBeCreatedWithUser()
    {
        //setting up scenario
        $attributes = array();

        $attributes['name'] = 'Mary Doe';
        $attributes['cpf'] = '33333333333';
        $attributes['email'] = 'marydoe@test3.com';

        User::factory()->create(['email' => 'marydoe@test3.com', 'employee_id' => null]);

        //execution 
        $this->service->create($attributes);
        
        //verifications
        $this->assertEquals('Mary Doe', Employee::find(3)->name);
        $this->assertEquals('marydoe@test3.com', Employee::find(3)->user->email);

        $this->assertEquals(3, Employee::all()->count());
    }

    /**
     * @test
     */
    public function employeeShouldBeUpdated()
    {
        //setting up scenario

        $employee = Employee::find(1);

        $attributes = array();

        $attributes['name'] = 'Bob Doe';
        $attributes['cpf'] = '44444444444';
        $attributes['email'] = 'bobdoe@test4.com';

        //execution
        $this->service->update($attributes, $employee);

        //verifications
        $this->assertEquals('Bob Doe', Employee::find(1)->name);
        $this->assertEquals('bobdoe@test4.com', Employee::find(1)->email);
        $this->assertEquals(2, Employee::all()->count());
    }

    /**
     * @test
     */
    public function employeeShouldBeDeleted()
    {
        //setting up scenario
        $employee = Employee::find(1);

        //execution 
        $this->service->delete($employee);

        //verifications
        $this->assertEquals('Jane Doe', $this->service->list()->first()->name);
        $this->assertEquals(1, $this->service->list()->count());
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

        //execution 
        $this->service->delete($employee);

        $user = User::find(1);
        
        //verifications
        $this->assertEquals('Jane Doe', $this->service->list()->first()->name);
        $this->assertNull($user->employee_id);
        $this->assertEquals(1, $this->service->list()->count());
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

        $bondDocument = BondDocument::factory()->create([
            'bond_id' => $bond->id,
        ]);

        //Log::error('Docuemtn name:' . $employee->bonds->first()->bondDocuments->first()->original_name);

        //execution 
        $this->service->delete($employee);

        //verifications
        $this->assertEquals('Jane Doe', $this->service->list()->first()->name);
        $this->assertEquals(1, $this->service->list()->count());
    }
}
