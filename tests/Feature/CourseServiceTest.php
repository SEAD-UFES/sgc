<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Course;
use App\Services\CourseService;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

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

        $this->service = new CourseService;
    }

    /**
     * @test
     */
    public function coursesShouldBeListed()
    {
        //verifications
        $this->assertEquals('Course Alpha', $this->service->list()->first()->name);
        $this->assertEquals(2, $this->service->list()->count());
    }

    /**
     * @test
     */
    public function courseShouldBeCreated()
    {
        //setting up scenario
        $attributes = array();

        $attributes['name'] = 'Course Gama';
        $attributes['description'] = '3rd course';
        $attributes['course_type_id'] = 2;
        $attributes['begin'] = now();
        $attributes['end'] = now();

        //execution 
        $this->service->create($attributes);

        //verifications
        $this->assertEquals('Course Gama', Course::find(3)->name);
        $this->assertEquals(3, Course::all()->count());
    }

    /**
     * @test
     */
    public function courseShouldBeUpdated()
    {
        //setting up scenario

        $course = Course::find(1);

        $attributes = array();

        $attributes['name'] = 'Course Delta';
        $attributes['description'] = 'New 1st course';

        //execution
        $this->service->update($attributes, $course);

        //verifications
        $this->assertEquals('Course Delta', Course::find(1)->name);
        $this->assertEquals('New 1st course', Course::find(1)->description);
        $this->assertEquals(2, Course::all()->count());
    }

    /**
     * @test
     */
    public function courseShouldBeDeleted()
    {
        //setting up scenario
        $course = Course::find(1);

        //execution 
        $this->service->delete($course);

        //verifications
        $this->assertEquals('Course Beta', $this->service->list()->first()->name);
        $this->assertEquals(1, $this->service->list()->count());
    }
}
