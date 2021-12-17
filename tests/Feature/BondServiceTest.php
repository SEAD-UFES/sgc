<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Bond;
use App\Models\Course;
use App\Models\Document;
use App\Models\Employee;
use App\Models\UserType;
use App\Models\BondDocument;
use App\Services\BondService;
use App\Models\EmployeeDocument;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;

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
        Event::fakeFor(function () {
            //execution
            $bonds = $this->service->list();

            //verifications
            Event::assertDispatched('eloquent.listed: ' . Bond::class);
            $this->assertCount(2, $bonds);
            $this->assertEquals('Course Alpha', $bonds[0]->course->name);
            $this->assertEquals('John Doe', $bonds[0]->employee->name);
            $this->assertEquals('Course Beta', $bonds[1]->course->name);
            $this->assertEquals('Jane Doe', $bonds[1]->employee->name);
        });
    }

    /**
     * @test
     */
    public function bondShouldBeRetrieved()
    {
        //setting up scenario
        $bond = Bond::find(1);

        Event::fakeFor(function () use ($bond) {
            //execution
            $bond = $this->service->read($bond);

            //verifications
            Event::assertDispatched('eloquent.fetched: ' . Bond::class);
            $this->assertEquals('Course Alpha', $bond->course->name);
            $this->assertCount(2, Bond::all());
        });
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

        //Should be mocked?
        UserType::create(['name' => 'Assistant', 'acronym' => 'ass', 'description' => '']);

        Event::fakeFor(function () use ($attributes) {
            //execution
            $this->service->create($attributes);

            //verifications
            Event::assertDispatched('eloquent.created: ' . Bond::class);
            $this->assertCount(3, Bond::all());
            $this->assertEquals('John Doe', Bond::find(3)->employee->name);
            $this->assertEquals('Course Beta', Bond::find(3)->course->name);
        });
    }

    /**
     * @test
     */
    public function bondShouldBeCreatedWithEmployeeDocument()
    {
        //setting up scenario
        $attributes = array();

        $attributes['employee_id'] = 1;
        $attributes['role_id'] = 2;
        $attributes['course_id'] = 2;
        $attributes['pole_id'] = 1;

        //Should be mocked?
        UserType::create(['name' => 'Assistant', 'acronym' => 'ass', 'description' => '']);

        Document::factory()->create([
            'original_name' => 'EmployeeDummyFile.pdf',
            'documentable_id' => EmployeeDocument::factory()->create(['employee_id' => $attributes['employee_id']])->id,
            'documentable_type' => 'App\Models\EmployeeDocument',
        ]);

        Event::fakeFor(function () use ($attributes) {
            //execution
            $this->service->create($attributes);

            //verifications
            Event::assertDispatched('eloquent.created: ' . Bond::class);
            $this->assertEquals('John Doe', Bond::find(3)->employee->name);
            $this->assertEquals('Course Beta', Bond::find(3)->course->name);
            $this->assertEquals('EmployeeDummyFile.pdf', Bond::find(3)->employee->EmployeeDocuments()->first()->document->original_name);
            $this->assertCount(3, Bond::all());
        });
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

        Event::fakeFor(function () use ($attributes, $bond) {
            //execution
            $this->service->update($attributes, $bond);

            //verifications
            Event::assertDispatched('eloquent.updated: ' . Bond::class);
            $this->assertEquals('Jane Doe', Bond::find(1)->employee->name);
            $this->assertEquals('Course Beta', Bond::find(1)->course->name);
            $this->assertCount(2, Bond::all());
        });
    }

    /**
     * @test
     */
    public function bondShouldBeDeleted()
    {
        //setting up scenario
        $bond = Bond::find(1);

        Event::fakeFor(function () use ($bond) {
            //execution
            $this->service->delete($bond);

            //verifications
            Event::assertDispatched('eloquent.deleted: ' . Bond::class);
            $this->assertEquals('Jane Doe', $this->service->list()->first()->employee->name);
            $this->assertCount(1, Bond::all());
        });
    }

    /**
     * @test
     */
    public function bondWithDocumentShouldBeDeleted()
    {
        //setting up scenario
        $bond = Bond::find(1);

        Document::factory()->create([
            'original_name' => 'BondDummyFile.pdf',
            'documentable_id' => BondDocument::factory()->create(['bond_id' => $bond->id])->id,
            'documentable_type' => 'App\Models\BondDocument',
        ]);

        Event::fakeFor(function () use ($bond) {
            //execution
            $this->service->delete($bond);

            //verifications
            Event::assertDispatched('eloquent.deleted: ' . Bond::class);
            $this->assertEquals('Jane Doe', $this->service->list()->first()->employee->name);
            $this->assertCount(1, Bond::all());
        });
    }
}
