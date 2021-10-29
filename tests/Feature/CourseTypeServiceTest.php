<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\CourseType;
use App\Services\CourseTypeService;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;

class CourseTypeServiceTest extends TestCase
{
    use RefreshDatabase;

    //setting up scenario for all tests
    public function setUp(): void
    {
        parent::setUp();

        CourseType::factory()->create(
            [
                'name' => 'Type Alpha',
                'description' => '1st type',
            ]
        );

        CourseType::factory()->create(
            [
                'name' => 'Type Beta',
                'description' => '2nd type',
            ]
        );

        $this->service = new CourseTypeService;
    }

    /**
     * @test
     */
    public function courseTypesShouldBeListed()
    {
        Event::fakeFor(function () {
            //execution
            $courseTypes = $this->service->list();

            //verifications
            $this->assertEquals('Type Alpha', $courseTypes->first()->name);
            $this->assertCount(2, $courseTypes);
        });
    }

    /**
     * @test
     */
    /*public function courseTypeShouldBeCreated()
    {
        //setting up scenario
        $attributes = array();

        $attributes['name'] = 'Type Gama';
        $attributes['description'] = '3rd type';

        //execution 
        $this->service->create($attributes);

        //verifications
        $this->assertEquals('Type Gama', CourseType::find(3)->name);
        $this->assertEquals(3, CourseType::all()->count());
    }

    /**
     * @test
     */
    /*public function courseTypeShouldBeUpdated()
    {
        //setting up scenario

        $courseType = CourseType::find(1);

        $attributes = array();

        $attributes['name'] = 'Type Delta';
        $attributes['description'] = 'New 1st type';

        //execution
        $this->service->update($attributes, $courseType);

        //verifications
        $this->assertEquals('Type Delta', CourseType::find(1)->name);
        $this->assertEquals('New 1st type', CourseType::find(1)->description);
        $this->assertEquals(2, CourseType::all()->count());
    }

    /**
     * @test
     */
    /*public function courseTypeShouldBeDeleted()
    {
        //setting up scenario
        $courseType = CourseType::find(1);

        //execution 
        $this->service->delete($courseType);

        //verifications
        $this->assertEquals('Type Beta', $this->service->list()->first()->name);
        $this->assertEquals(1, $this->service->list()->count());
    }*/
}
