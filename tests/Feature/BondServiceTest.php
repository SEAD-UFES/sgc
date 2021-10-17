<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Bond;
use App\Models\Course;
use App\Models\Employee;
use App\Models\UserType;
use App\Services\BondService;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BondServiceTest extends TestCase
{
    use RefreshDatabase;

    //setting up scenario for all tests
    public function setUp(): void
    {
        parent::setUp();

        Bond::factory()->create(
            [
                'course_id' => Course::factory()->create(
                    [
                        'name' => 'Course Alpha',
                    ]
                ),
                'employee_id' => Employee::factory()->create(
                    [
                        'name' => 'John Doe',
                        'email' => 'john@test.com',
                    ]
                ),
            ]
        );

        Bond::factory()->create(
            [
                'course_id' => Course::factory()->create(
                    [
                        'name' => 'Course Beta',
                    ]
                ),
                'employee_id' => Employee::factory()->create(
                    [
                        'name' => 'Jane Doe',
                        'email' => 'jane@othertest.com',
                    ]
                ),
            ]
        );

        $this->service = new BondService;
    }

    /**
     * @test
     */
    public function bondsShouldBeListed()
    {
        //verifications
        $this->assertEquals('John Doe', $this->service->list()->first()->employee->name);
        $this->assertEquals(2, $this->service->list()->count());
    }

    /**
     * @test
     */
    public function bondShouldBeCreated()
    {
        //setting up scenario
        $attributes = array();

        $attributes['employee_id'] = 1;
        $attributes['role_id'] = 2;
        $attributes['course_id'] = 2;
        $attributes['pole_id'] = 1;

        //Should be mocked(?)
        UserType::create(['name' => 'Assistant', 'acronym' => 'ass', 'description' => '']);

        //execution 
        $this->service->create($attributes);

        //verifications
        $this->assertEquals('John Doe', Bond::find(3)->employee->name);
        $this->assertEquals('Course Beta', Bond::find(3)->course->name);
        $this->assertEquals(3, Bond::all()->count());
    }

    /**
     * @test
     */
    public function bondShouldBeUpdated()
    {
        //setting up scenario

        $bond = Bond::find(1);

        $attributes = array();

        $attributes['employee_id'] = 2;
        $attributes['course_id'] = 2;

        //execution array $attributes, Bond $bond
        $this->service->update($attributes, $bond);

        //verifications
        $this->assertEquals('Jane Doe', Bond::find(1)->employee->name);
        $this->assertEquals('Course Beta', Bond::find(1)->course->name);
        $this->assertEquals(2, Bond::all()->count());
    }

    /**
     * @test
     */
    public function bondShouldBeDeleted()
    {
        //setting up scenario
        $bond = Bond::find(1);

        //execution 
        $this->service->delete($bond);

        //verifications
        $this->assertEquals('Jane Doe', $this->service->list()->first()->employee->name);
        $this->assertEquals(1, $this->service->list()->count());
    }

}
