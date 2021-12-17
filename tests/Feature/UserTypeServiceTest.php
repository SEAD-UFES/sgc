<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\UserType;
use App\Services\UserTypeService;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;

class UserTypeServiceTest extends TestCase
{
    use RefreshDatabase;

    //setting up scenario for all tests
    public function setUp(): void
    {
        parent::setUp();

        UserType::factory()->create(
            [
                'name' => 'Type Alpha',
                'acronym' => 'alp',
                'description' => '1st type',
            ]
        );

        UserType::factory()->create(
            [
                'name' => 'Type Beta',
                'acronym' => 'bet',
                'description' => '2nd type',
            ]
        );

        $this->service = new UserTypeService;
    }

    /**
     * @test
     */
    public function userTypesShouldBeListed()
    {
        Event::fakeFor(function () {
            $userTypes = $this->service->list();

            //verifications
            $this->assertEquals('Type Alpha', $userTypes->first()->name);
            $this->assertCount(2, $userTypes);
        });
    }

    /**
     * @test
     */
    /*public function userTypeShouldBeCreated()
    {
        //setting up scenario
        $attributes = array();

        $attributes['name'] = 'Type Gama';
        $attributes['acronym'] = 'gam';
        $attributes['description'] = '3rd type';

        //execution
        $this->service->create($attributes);

        //verifications
        $this->assertEquals('Type Gama', UserType::find(3)->name);
        $this->assertEquals(3, UserType::all()->count());
    }

    /**
     * @test
     */
    /*public function userTypeShouldBeUpdated()
    {
        //setting up scenario

        $userType = UserType::find(1);

        $attributes = array();

        $attributes['name'] = 'Type Delta';
        $attributes['acronym'] = 'del';
        $attributes['description'] = 'New 1st type';

        //execution
        $this->service->update($attributes, $userType);

        //verifications
        $this->assertEquals('Type Delta', UserType::find(1)->name);
        $this->assertEquals('New 1st type', UserType::find(1)->description);
        $this->assertEquals(2, UserType::all()->count());
    }

    /**
     * @test
     */
    /*public function userTypeShouldBeDeleted()
    {
        //setting up scenario
        $userType = UserType::find(1);

        //execution
        $this->service->delete($userType);

        //verifications
        $this->assertEquals('Type Beta', $this->service->list()->first()->name);
        $this->assertEquals(1, $this->service->list()->count());
    }*/
}
