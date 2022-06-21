<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Services\CourseService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class CourseServiceTest extends TestCase
{
    use RefreshDatabase;

    //setting up scenario for all tests
    public function setUp(): void
    {
        parent::setUp();

        Course::factory()->create(
            [
                'name' => 'Course Alpha',
            ]
        );

        Course::factory()->create(
            [
                'name' => 'Course Beta',
            ]
        );

        $this->service = new CourseService();
    }

    /**
     * @test
     */
    public function coursesShouldBeListed()
    {
        Event::fakeFor(function () {
            //execution
            $courses = $this->service->list();

            //verifications
            Event::assertDispatched('eloquent.listed: ' . Course::class);
            $this->assertEquals('Course Alpha', $courses->first()->name);
            $this->assertCount(2, $courses);
        });
    }

    /**
     * @test
     */
    public function courseShouldBeRetrieved()
    {
        //setting up scenario
        $course = Course::find(1);

        Event::fakeFor(function () use ($course) {
            //execution
            $course = $this->service->read($course);

            //verifications
            Event::assertDispatched('eloquent.fetched: ' . Course::class);
            $this->assertEquals('Course Alpha', $course->name);
            $this->assertCount(2, Course::all());
        });
    }

    /**
     * @test
     */
    public function courseShouldBeCreated()
    {
        //setting up scenario
        $attributes = [];

        $attributes['name'] = 'Course Gama';
        $attributes['description'] = '3rd course';
        $attributes['course_type_id'] = 2;
        $attributes['begin'] = now();
        $attributes['end'] = now();

        //execution
        Event::fakeFor(function () use ($attributes) {
            $this->service->create($attributes);

            //verifications
            Event::assertDispatched('eloquent.created: ' . Course::class);
            $this->assertEquals('Course Gama', Course::find(3)->name);
            $this->assertEquals('3rd course', Course::find(3)->description);
            $this->assertCount(3, Course::all());
        });
    }

    /**
     * @test
     */
    public function courseShouldBeUpdated()
    {
        //setting up scenario

        $course = Course::find(1);

        $attributes = [];

        $attributes['name'] = 'Course Delta';
        $attributes['description'] = 'New 1st course';

        Event::fakeFor(function () use ($course, $attributes) {
            //execution
            $this->service->update($attributes, $course);

            //verifications
            Event::assertDispatched('eloquent.updated: ' . Course::class);
            $this->assertEquals('Course Delta', Course::find(1)->name);
            $this->assertEquals('New 1st course', Course::find(1)->description);
            $this->assertCount(2, Course::all());
        });
    }

    /**
     * @test
     */
    public function courseShouldBeDeleted()
    {
        //setting up scenario
        $course = Course::find(1);

        Event::fakeFor(function () use ($course) {
            //execution
            $this->service->delete($course);

            //verifications
            Event::assertDispatched('eloquent.deleted: ' . Course::class);
            $this->assertEquals('Course Beta', $this->service->list()->first()->name);
            $this->assertCount(1, Course::all());
        });
    }
}
