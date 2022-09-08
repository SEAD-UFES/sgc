<?php

namespace Tests\Feature;

use App\Events\ModelListed;
use App\Events\ModelRead;
use App\Models\Course;
use App\Models\User;
use App\Models\UserType;
use App\Models\Responsibility;
use App\Services\ResponsibilityService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class ResponsibilityServiceTest extends TestCase
{
    use RefreshDatabase;

    //setting up scenario for all tests
    public function setUp(): void
    {
        parent::setUp();

        Responsibility::factory()->create(
            [
                'user_id' => User::factory()->create(['email' => 'johndoe@test.com']),
                'user_type_id' => UserType::factory()->create(['name' => 'Type one']),
                'course_id' => Course::factory()->create(['name' => 'Course Alpha']),
                'begin' => now(),
                'end' => now(),
            ]
        );

        Responsibility::factory()->create(
            [
                'user_id' => User::factory()->create(['email' => 'janedoe@test2.com']),
                'user_type_id' => UserType::factory()->create(['name' => 'Type two']),
                'course_id' => Course::factory()->create(['name' => 'Course Alpha']),
                'begin' => now(),
                'end' => now(),
            ]
        );

        $this->service = new ResponsibilityService();
    }

    /**
     * @test
     */
    public function responsibilitiesShouldBeListed()
    {
        Event::fakeFor(function () {
            //execution
            $responsibilities = $this->service->list();

            //verifications
            Event::assertDispatched(ModelListed::class);
            $this->assertEquals('johndoe@test.com', Responsibility::find(1)->user->email);
            $this->assertCount(2, $responsibilities);
        });
    }

    /**
     * @test
     */
    public function responsibilityShouldBeRetrieved()
    {
        //setting up scenario
        $responsibility = Responsibility::find(1);

        Event::fakeFor(function () use ($responsibility) {
            //execution
            $responsibility = $this->service->read($responsibility);

            //verifications
            Event::assertDispatched(ModelRead::class);
            $this->assertEquals('johndoe@test.com', $responsibility->user->email);
            $this->assertCount(2, Responsibility::all());
        });
    }

    /**
     * @test
     */
    public function responsibilityShouldBeCreated()
    {
        //setting up scenario
        $attributes = [];

        $attributes['user_id'] = 2;
        $attributes['user_type_id'] = 1;
        $attributes['course_id'] = 2;
        $attributes['begin'] = now();
        $attributes['end'] = now();

        Event::fakeFor(function () use ($attributes) {
            //execution
            $responsibility = $this->service->create($attributes);

            //verifications
            Event::assertDispatched('eloquent.created: ' . Responsibility::class);
            $this->assertEquals('janedoe@test2.com', Responsibility::find(3)->user->email);
            $this->assertEquals('Type one', Responsibility::find(3)->userType->name);
            $this->assertCount(3, Responsibility::all());
        });
    }

    /**
     * @test
     */
    public function responsibilityShouldBeUpdated()
    {
        //setting up scenario

        $responsibility = Responsibility::find(1);

        $newUser = User::factory()->create(['email' => 'bobdoe@test3.com']);
        $newUserType = UserType::factory()->create(['name' => 'Type three']);
        $newCourse = Course::factory()->create(['name' => 'Course Gama']);

        $attributes = [];

        $attributes['user_id'] = $newUser->id;
        $attributes['user_type_id'] = $newUserType->id;
        $attributes['course_id'] = $newCourse->id;
        $attributes['begin'] = now();
        $attributes['end'] = now();

        Event::fakeFor(function () use ($attributes, $responsibility) {
            //execution
            $this->service->update($attributes, $responsibility);

            //verifications
            Event::assertDispatched('eloquent.updated: ' . Responsibility::class);
            $this->assertEquals('bobdoe@test3.com', Responsibility::find(1)->user->email);
            $this->assertEquals('Course Gama', Responsibility::find(1)->course->name);
            $this->assertCount(2, Responsibility::all());
        });
    }

    /**
     * @test
     */
    public function responsibilityShouldBeDeleted()
    {
        //setting up scenario
        $responsibility = Responsibility::find(1);

        Event::fakeFor(function () use ($responsibility) {
            //execution
            $this->service->delete($responsibility);

            //verifications
            Event::assertDispatched('eloquent.deleted: ' . Responsibility::class);
            $this->assertEquals('janedoe@test2.com', $this->service->list()->first()->user->email);
            $this->assertCount(1, Responsibility::all());
        });
    }
}
