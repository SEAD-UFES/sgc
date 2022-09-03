<?php

namespace Tests\Feature;

use App\Enums\KnowledgeAreas;
use App\Events\ModelListed;
use App\Events\ModelRead;
use App\Models\Bond;
use App\Models\BondDocument;
use App\Models\Course;
use App\Models\Document;
use App\Models\Employee;
use App\Models\EmployeeDocument;
use App\Models\UserType;
use App\Services\BondService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class BondServiceTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * @var BondService
     */
    private BondService $service;

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

        $this->service = new BondService();
    }

    /**
     * @test
     */
    public function bondsShouldBeListed(): void
    {
        Event::fakeFor(function () {
            //execution
            $bonds = $this->service->list();

            $employeesNames = [];
            $employeesCourses = [];

            /**
             * @var Bond $bond
             */
            foreach ($bonds as $bond) {
                $this->assertInstanceOf(Bond::class, $bond);
                $employeesNames[] = $bond->employee?->name;
                $employeesCourses[] = $bond->course?->name;
            }

            //verifications
            Event::assertDispatched(ModelListed::class);
            $this->assertCount(2, $bonds);
            $this->assertContains('John Doe', $employeesNames);
            $this->assertContains('Jane Doe', $employeesNames);
            $this->assertContains('Course Alpha', $employeesCourses);
            $this->assertContains('Course Beta', $employeesCourses);
        });
    }

    /**
     * @test
     */
    public function bondShouldBeRetrieved(): void
    {
        //setting up scenario
        $bond = Bond::find(1);

        Event::fakeFor(function () use ($bond) {
            //execution
            $bond = $this->service->read($bond);

            //verifications
            Event::assertDispatched(ModelRead::class);
            $this->assertEquals('Course Alpha', $bond->course?->name);
            $this->assertCount(2, Bond::all());
        });
    }

    /**
     * @test
     */
    public function bondShouldBeCreated(): void
    {
        //setting up scenario
        $attributes = [];

        $attributes['employee_id'] = 1;
        $attributes['role_id'] = 2;
        $attributes['course_id'] = 2;
        $attributes['pole_id'] = 1;
        $attributes = array_merge($attributes, $this->getQualificationAttributes());

        //Should be mocked?
        UserType::create(['name' => 'Assistant', 'acronym' => 'ass', 'description' => '']);

        Event::fakeFor(function () use ($attributes) {
            //execution
            $this->service->create($attributes);

            //verifications
            Event::assertDispatched('eloquent.created: ' . Bond::class);
            $this->assertCount(3, Bond::all());
            $this->assertEquals('John Doe', Bond::find(3)?->employee?->name);
            $this->assertEquals('Course Beta', Bond::find(3)?->course?->name);
        });
    }

    /**
     * @test
     */
    public function bondShouldBeCreatedWithEmployeeDocument(): void
    {
        //setting up scenario
        $attributes = [];

        $attributes['employee_id'] = 1;
        $attributes['role_id'] = 2;
        $attributes['course_id'] = 2;
        $attributes['pole_id'] = 1;
        $attributes = array_merge($attributes, $this->getQualificationAttributes());

        //Should be mocked?
        UserType::create(['name' => 'Assistant', 'acronym' => 'ass', 'description' => '']);

        Document::factory()->create([
            'original_name' => 'EmployeeDummyFile.pdf',
            'documentable_id' => EmployeeDocument::factory()->createOne(['employee_id' => $attributes['employee_id']])->id,
            'documentable_type' => 'App\Models\EmployeeDocument',
        ]);

        Event::fakeFor(function () use ($attributes) {
            //execution
            $this->service->create($attributes);

            //verifications
            Event::assertDispatched('eloquent.created: ' . Bond::class);
            $this->assertEquals('John Doe', Bond::find(3)?->employee?->name);
            $this->assertEquals('Course Beta', Bond::find(3)?->course?->name);
            $this->assertEquals('EmployeeDummyFile.pdf', Bond::find(3)?->employee?->EmployeeDocuments()->first()?->document?->original_name);
            $this->assertCount(3, Bond::all());
        });
    }

    /**
     * @test
     */
    public function bondShouldBeUpdated(): void
    {
        //setting up scenario

        $bond = Bond::find(1);

        $attributes = [];

        $attributes['employee_id'] = 2;
        $attributes['course_id'] = 2;
        $attributes = array_merge($attributes, $this->getQualificationAttributes());

        Event::fakeFor(function () use ($attributes, $bond) {
            //execution
            $this->service->update($attributes, $bond);

            //verifications
            Event::assertDispatched('eloquent.updated: ' . Bond::class);
            $this->assertEquals('Jane Doe', Bond::find(1)?->employee?->name);
            $this->assertEquals('Course Beta', Bond::find(1)?->course?->name);
            $this->assertCount(2, Bond::all());
        });
    }

    /**
     * @test
     */
    public function bondShouldBeDeleted(): void
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
    public function bondWithDocumentShouldBeDeleted(): void
    {
        //setting up scenario
        $bond = Bond::find(1);

        Document::factory()->create([
            'original_name' => 'BondDummyFile.pdf',
            'documentable_id' => BondDocument::factory()->createOne(['bond_id' => $bond?->id])->id,
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

    /**
     * @return array<string, string>
     */
    private function getQualificationAttributes(): array
    {
        return [
            'knowledge_area' => strval($this->faker->randomElement(KnowledgeAreas::getValuesInAlphabeticalOrder())),
            'course_name' => 'Test Course',
            'institution_name' => 'Test Institution',
        ];
    }
}
