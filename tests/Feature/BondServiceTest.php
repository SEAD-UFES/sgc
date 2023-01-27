<?php

namespace Tests\Feature;

use App\Enums\KnowledgeAreas;
use App\Events\ModelListed;
use App\Events\ModelRead;
use App\Models\Bond;
use App\Models\BondCourse;
use App\Models\BondPole;
use App\Models\Course;
use App\Models\CourseClass;
use App\Models\Document;
use App\Models\Employee;
use App\Models\Pole;
use App\Models\Role;
use App\Models\User;
use App\Models\UserType;
use App\Repositories\DocumentRepository;
use App\Services\BondService;
use App\Services\Dto\BondDto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
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

        User::factory()->withoutEmployee()->create([
            'login' => 'sgc_system',
        ]);

        $bond = new Bond([
            'employee_id' => Employee::factory()->createOne(
                [
                    'name' => 'John Doe',
                    'email' => 'john@test.com',
                ]
            )->getAttribute('id'),
            'role_id' => Role::factory()->createOne()->getAttribute('id'),
            'volunteer' => false,
            'hiring_process' => '123/2023',
            'begin' => Carbon::now()->subYear(),
            'terminated_at' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $bond->save();
        $bondId = $bond->getAttribute('id');

        $courseId = Course::factory()->createOne(
            [
                'name' => 'Course Alpha',
            ]
        )->getAttribute('id');
         $bondCourse = new BondCourse(
             [
                'bond_id' => $bondId,
                'course_id' => $courseId,
             ]
         );
        $bondCourse->save();
        $poleId = Pole::factory()->createOne(
            [
                'name' => 'Pole Alpha',
            ]
        )->getAttribute('id');
        $bondPole = new BondPole(
            [
                'bond_id' => $bondId,
                'pole_id' => $poleId,
            ]
        );
        $bondPole->save();

        $bond = new Bond([
            'employee_id' => Employee::factory()->createOne(
                [
                    'name' => 'Jane Doe',
                    'email' => 'jane@othertest.com',
                ]
            )->getAttribute('id'),
            'role_id' => Role::factory()->createOne()->getAttribute('id'),
            'volunteer' => false,
            'hiring_process' => '123/2023',
            'begin' => Carbon::now()->subYear(),
            'terminated_at' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $bond->save();
        $bondId = $bond->getAttribute('id');

        $courseId = Course::factory()->createOne(
            [
                'name' => 'Course Beta',
            ]
        )->getAttribute('id');
        $bondCourse = new BondCourse(
            [
                'bond_id' => $bondId,
                'course_id' => $courseId,
            ]
        );
        $bondCourse->save();
        $poleId = Pole::factory()->createOne(
            [
                'name' => 'Pole Beta',
            ]
        )->getAttribute('id');
        $bondPole = new BondPole(
            [
                'bond_id' => $bondId,
                'pole_id' => $poleId,
            ]
        );
        $bondPole->save();

        (new CourseClass(
            [
                'course_id' => 1,
                'code' => '123',
                'name' => 'Class Zeta',
                'cpp' => 10,
            ]
        ))->save();

        $this->service = new BondService(new DocumentRepository());
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

        $attributes['employeeId'] = 1;
        $attributes['roleId'] = 2;
        $attributes['courseId'] = 2;
        $attributes['courseClassId'] = 1;
        $attributes['poleId'] = 1;
        $attributes['begin'] = new Carbon('2022-01-01');
        $attributes['terminatedAt'] = null;
        $attributes['hiringProcess'] = '01/2022';
        $attributes['volunteer'] = false;
        $attributes = array_merge($attributes, $this->getQualificationAttributes());

        $dto = new BondDto(...$attributes);

        //Should be mocked?
        UserType::create(['name' => 'Assistant', 'acronym' => 'ass', 'description' => '']);

        Event::fakeFor(function () use ($dto) {
            //execution
            $this->service->create($dto);

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
    public function bondShouldBeUpdated(): void
    {
        //setting up scenario

        $bond = Bond::find(1);

        $attributes = [];

        $attributes['employeeId'] = 2;
        $attributes['roleId'] = 2;
        $attributes['courseId'] = 2;
        $attributes['courseClassId'] = 1;
        $attributes['poleId'] = 1;
        $attributes['begin'] = new Carbon('2022-01-01');
        $attributes['terminatedAt'] = null;
        $attributes['hiringProcess'] = '01/2022';
        $attributes['volunteer'] = false;
        $attributes = array_merge($attributes, $this->getQualificationAttributes());

        $dto = new BondDto(...$attributes);

        Event::fakeFor(function () use ($dto, $bond) {
            //execution
            $this->service->update($dto, $bond);

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

        Document::factory()->createOne([
            'file_name' => 'BondDummyFile.pdf',
            'related_id' => $bond->getAttribute('id'),
            'related_type' => Bond::class,
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
            'qualificationKnowledgeArea' => $this->faker->randomElement(KnowledgeAreas::cases()),
            'qualificationCourse' => 'Test Course',
            'qualificationInstitution' => 'Test Institution',
        ];
    }
}
