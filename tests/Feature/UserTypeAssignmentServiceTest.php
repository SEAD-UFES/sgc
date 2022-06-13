<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Course;
use App\Models\UserType;
use App\Models\UserTypeAssignment;
use App\Services\UserTypeAssignmentService;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;

class UserTypeAssignmentServiceTest extends TestCase
{
    use RefreshDatabase;

    //setting up scenario for all tests
    public function setUp(): void
    {
        parent::setUp();

        UserTypeAssignment::factory()->create(
            [
                'user_id' => User::factory()->create(['email' => 'johndoe@test.com']),
                'user_type_id' => UserType::factory()->create(['name' => 'Type one']),
                'course_id' => Course::factory()->create(['name' => 'Course Alpha']),
                'begin' => now(),
                'end' => now(),
            ]
        );

        UserTypeAssignment::factory()->create(
            [
                'user_id' => User::factory()->create(['email' => 'janedoe@test2.com']),
                'user_type_id' => UserType::factory()->create(['name' => 'Type two']),
                'course_id' => Course::factory()->create(['name' => 'Course Alpha']),
                'begin' => now(),
                'end' => now(),
            ]
        );

        $this->service = new UserTypeAssignmentService;
    }

    /**
     * @test
     */
    public function userTypeAssignmentsShouldBeListed()
    {
        Event::fakeFor(function () {
            //execution
            $userTypeAssignments = $this->service->list();

            //verifications
            Event::assertDispatched('eloquent.listed: ' . UserTypeAssignment::class);
            $this->assertEquals('johndoe@test.com', UserTypeAssignment::find(1)->user->email);
            $this->assertCount(2, $userTypeAssignments);
        });
    }

    /**
     * @test
     */
    public function userTypeAssignmentShouldBeRetrieved()
    {
        //setting up scenario
        $userTypeAssignment = UserTypeAssignment::find(1);

        Event::fakeFor(function () use ($userTypeAssignment) {
            //execution
            $userTypeAssignment = $this->service->read($userTypeAssignment);

            //verifications
            Event::assertDispatched('eloquent.fetched: ' . UserTypeAssignment::class);
            $this->assertEquals('johndoe@test.com', $userTypeAssignment->user->email);
            $this->assertCount(2, UserTypeAssignment::all());
        });
    }

    /**
     * @test
     */
    public function userTypeAssignmentShouldBeCreated()
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
            $userTypeAssignment = $this->service->create($attributes);

            //verifications
            Event::assertDispatched('eloquent.created: ' . UserTypeAssignment::class);
            $this->assertEquals('janedoe@test2.com', UserTypeAssignment::find(3)->user->email);
            $this->assertEquals('Type one', UserTypeAssignment::find(3)->userType->name);
            $this->assertCount(3, UserTypeAssignment::all());
        });
    }

    /**
     * @test
     */
    public function userTypeAssignmentShouldBeUpdated()
    {
        //setting up scenario

        $userTypeAssignment = UserTypeAssignment::find(1);

        $newUser = User::factory()->create(['email' => 'bobdoe@test3.com']);
        $newUserType = UserType::factory()->create(['name' => 'Type three']);
        $newCourse = Course::factory()->create(['name' => 'Course Gama']);

        $attributes = [];

        $attributes['user_id'] = $newUser->id;
        $attributes['user_type_id'] = $newUserType->id;
        $attributes['course_id'] = $newCourse->id;
        $attributes['begin'] = now();
        $attributes['end'] = now();

        Event::fakeFor(function () use ($attributes, $userTypeAssignment) {
            //execution
            $this->service->update($attributes, $userTypeAssignment);

            //verifications
            Event::assertDispatched('eloquent.updated: ' . UserTypeAssignment::class);
            $this->assertEquals('bobdoe@test3.com', UserTypeAssignment::find(1)->user->email);
            $this->assertEquals('Course Gama', UserTypeAssignment::find(1)->course->name);
            $this->assertCount(2, UserTypeAssignment::all());
        });
    }

    /**
     * @test
     */
    public function userTypeAssignmentShouldBeDeleted()
    {
        //setting up scenario
        $userTypeAssignment = UserTypeAssignment::find(1);

        Event::fakeFor(function () use ($userTypeAssignment) {
            //execution
            $this->service->delete($userTypeAssignment);

            //verifications
            Event::assertDispatched('eloquent.deleted: ' . UserTypeAssignment::class);
            $this->assertEquals('janedoe@test2.com', $this->service->list()->first()->user->email);
            $this->assertCount(1, UserTypeAssignment::all());
        });
    }
}
