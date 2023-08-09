<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use App\Events\ModelListed;
use App\Events\ModelRead;
use App\Models\Course;
use App\Models\User;
use App\Models\UserType;
use App\Models\Responsibility;
use App\Services\Dto\ResponsibilityDto;
use App\Services\ResponsibilityService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class ResponsibilityServiceTest extends TestCase
{
    use RefreshDatabase;

    //setting up scenario for all tests
    protected function setUp(): void
    {
        parent::setUp();

        Responsibility::factory()->create(
            [
                'user_id' => User::factory()->create(['login' => 'johndoe@test.com']),
                'user_type_id' => UserType::factory()->create(['name' => 'Type one']),
                'course_id' => Course::factory()->create(['name' => 'Course Alpha']),
                'begin' => now(),
                'end' => now(),
            ]
        );

        Responsibility::factory()->create(
            [
                'user_id' => User::factory()->create(['login' => 'janedoe@test2.com']),
                'user_type_id' => UserType::factory()->create(['name' => 'Type two']),
                'course_id' => Course::factory()->create(['name' => 'Course Alpha']),
                'begin' => now(),
                'end' => now(),
            ]
        );

        $this->service = new ResponsibilityService();
    }

    #[Test]
    public function responsibilitiesShouldBeListed()
    {
        Event::fakeFor(function () {
            //execution
            $responsibilities = $this->service->list();

            //verifications
            Event::assertDispatched(ModelListed::class);
            $this->assertEquals('johndoe@test.com', Responsibility::find(1)->user->login);
            $this->assertCount(2, $responsibilities);
        });
    }

    #[Test]
    public function responsibilityShouldBeRetrieved()
    {
        //setting up scenario
        $responsibility = Responsibility::find(1);

        Event::fakeFor(function () use ($responsibility) {
            //execution
            $responsibility = $this->service->read($responsibility);

            //verifications
            Event::assertDispatched(ModelRead::class);
            $this->assertEquals('johndoe@test.com', $responsibility->user->login);
            $this->assertCount(2, Responsibility::all());
        });
    }

    #[Test]
    public function responsibilityShouldBeCreated()
    {
        //setting up scenario
        $attributes = [];

        $attributes['userId'] = 2;
        $attributes['userTypeId'] = 1;
        $attributes['courseId'] = 2;
        $attributes['begin'] = now();
        $attributes['end'] = now();

        $dto = new ResponsibilityDto(...$attributes);

        Event::fakeFor(function () use ($dto) {
            //execution
            $responsibility = $this->service->create($dto);

            //verifications
            Event::assertDispatched('eloquent.created: ' . Responsibility::class);
            $this->assertEquals('janedoe@test2.com', Responsibility::find(3)->user->login);
            $this->assertEquals('Type one', Responsibility::find(3)->userType->name);
            $this->assertCount(3, Responsibility::all());
        });
    }

    #[Test]
    public function responsibilityShouldBeUpdated()
    {
        //setting up scenario

        $responsibility = Responsibility::find(1);

        $newUser = User::factory()->create(['login' => 'bobdoe@test3.com']);
        $newUserType = UserType::factory()->create(['name' => 'Type three']);
        $newCourse = Course::factory()->create(['name' => 'Course Gama']);

        $attributes = [];

        $attributes['userId'] = $newUser->id;
        $attributes['userTypeId'] = $newUserType->id;
        $attributes['courseId'] = $newCourse->id;
        $attributes['begin'] = now();
        $attributes['end'] = now();

        $dto = new ResponsibilityDto(...$attributes);

        Event::fakeFor(function () use ($dto, $responsibility) {
            //execution
            $this->service->update($dto, $responsibility);

            //verifications
            Event::assertDispatched('eloquent.updated: ' . Responsibility::class);
            $this->assertEquals('bobdoe@test3.com', Responsibility::find(1)->user->login);
            $this->assertEquals('Course Gama', Responsibility::find(1)->course->name);
            $this->assertCount(2, Responsibility::all());
        });
    }

    #[Test]
    public function responsibilityShouldBeDeleted()
    {
        //setting up scenario
        $responsibility = Responsibility::find(1);

        Event::fakeFor(function () use ($responsibility) {
            //execution
            $this->service->delete($responsibility);

            //verifications
            Event::assertDispatched('eloquent.deleted: ' . Responsibility::class);
            $this->assertEquals('janedoe@test2.com', $this->service->list()->first()->user->login);
            $this->assertCount(1, Responsibility::all());
        });
    }
}
