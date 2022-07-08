<?php

namespace Tests\Feature;

use App\Models\Approved;
use App\Models\ApprovedState;
use App\Models\Course;
use App\Models\Pole;
use App\Models\Role;
use App\Services\ApprovedService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class ApprovedServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var ApprovedService $service
     */
    private $service;

    //setting up scenario for all tests
    public function setUp(): void
    {
        parent::setUp();

        Approved::factory()->create(
            [
                'name' => 'John Doe',
                'email' => 'john@test.com',
                'area_code' => '01',
                'phone' => '12345678',
                'mobile' => '123456789',
                'announcement' => '001',
            ]
        );

        Approved::factory()->create(
            [
                'name' => 'Jane Doe',
                'email' => 'jane@othertest.com',
                'area_code' => '02',
                'phone' => '01234567',
                'mobile' => '012345678',
                'announcement' => '002',
            ]
        );

        $this->service = new ApprovedService();
    }

    /**
     * @test
     *
     * @return void
     */
    public function approvedsShouldBeListed()
    {
        Event::fakeFor(function () {
            //execution
            $approveds = $this->service->list();

            //verifications
            Event::assertDispatched('eloquent.listed: ' . Approved::class);
            $this->assertContains('John Doe', $approveds->pluck('name')->toArray());
            $this->assertContains('Jane Doe', $approveds->pluck('name')->toArray());
            $this->assertCount(2, $approveds);
        });
    }

    /**
     * @test
     *
     * @return void
     */
    public function approvedShouldBeRetrieved()
    {
        //setting up scenario
        /**
         * @var Approved $approved
         */
        $approved = Approved::find(1);

        Event::fakeFor(function () use ($approved) {
            //execution
            $approved = $this->service->read($approved);

            //verifications
            Event::assertDispatched('eloquent.fetched: ' . Approved::class);
            $this->assertEquals('John Doe', $approved->name);
            $this->assertCount(2, Approved::all());
        });
    }

    /**
     * @test
     *
     * @return void
     */
    public function approvedShouldBeDeleted()
    {
        //setting up scenario
        /**
         * @var Approved $approved
         */
        $approved = Approved::find(1);

        Event::fakeFor(function () use ($approved) {
            //execution
            $this->service->delete($approved);

            //verifications
            Event::assertDispatched('eloquent.deleted: ' . Approved::class);
            $this->assertEquals('Jane Doe', $this->service->list()->first()->name);
            $this->assertCount(1, Approved::all());
        });
    }

    /**
     * @test
     *
     * @return void
     */
    public function approvedStateShouldChange()
    {
        //setting up scenario
        $state_id = ApprovedState::factory()->createOne(
            [
                'name' => 'Foo',
                'description' => 'Bar',
            ]
        )->getAttribute('id');

        /**
         * @var array<string, string> $attributes
         */
        $attributes = [];
        $attributes['states'] = $state_id;

        /**
         * @var Approved $approved
         */
        $approved = Approved::find(1);

        Event::fakeFor(function () use ($approved, $attributes) {
            //execution
            $this->service->changeState($attributes, $approved);

            //verifications
            Event::assertDispatched('eloquent.saved: ' . Approved::class);
            $this->assertEquals('Foo', $approved->approvedState->name);
            $this->assertCount(2, Approved::all());
        });
    }

    /**
     * @test
     *
     * @return void
     */
    public function approvedShouldBeCreated()
    {
        //setting up scenario
        ApprovedState::factory()->create(
            [
                'name' => 'Não contatado',
                'description' => 'Bar',
            ]
        );

        /**
         * @var array<string, string> $attributes
         */
        $attributes = [];

        $attributes['name'] = 'Dilan Doe';
        $attributes['email'] = 'dilan@othertest.com';
        $attributes['area_code'] = '03';
        $attributes['phone'] = '01234567';
        $attributes['mobile'] = '012345678';
        $attributes['announcement'] = '003';

        $attributes['role_id'] = Role::factory()->createOne(
            [
                'name' => 'Super Role',
                'description' => 'Super Role',
            ]
        )->getAttribute('id');

        $attributes['course_id'] = Course::factory()->createOne(
            [
                'name' => 'Course Omicron',
                'description' => 'Course Omicron',
            ]
        )->getAttribute('id');

        $attributes['pole_id'] = Pole::factory()->createOne(
            [
                'name' => 'Pole Teta',
                'description' => 'Pole Teta',
            ]
        )->getAttribute('id');

        Event::fakeFor(function () use ($attributes) {
            //execution
            $this->service->create($attributes);

            //verifications
            Event::assertDispatched('eloquent.created: ' . Approved::class);
            $this->assertEquals('Dilan Doe', Approved::find(3)?->name);
            $this->assertCount(3, Approved::all());
        });
    }

    /**
     * @test
     *
     * @return void
     */
    public function shouldPersistApprovedsList()
    {
        //setting up scenario
        ApprovedState::factory()->createOne(
            [
                'name' => 'Não contatado',
                'description' => 'Bar',
            ]
        );

        $approveds = [];

        $approveds[0]['check'] = true;
        $approveds[0]['name'] = 'Bob Doe';
        $approveds[0]['email'] = 'bob@test3.com';
        $approveds[0]['announcement'] = '003';

        $approveds[1]['check'] = true;
        $approveds[1]['name'] = 'Mary Doe';
        $approveds[1]['email'] = 'mary@test4.com';
        $approveds[1]['announcement'] = '004';

        $attributes = [];
        $attributes['approveds'] = $approveds;

        //execution
        $this->service->batchStore($attributes);

        //verifications
        $this->assertEquals('Bob Doe', Approved::find(3)?->name);
        $this->assertEquals('mary@test4.com', Approved::find(4)?->email);
    }
}
