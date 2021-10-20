<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Pole;
use App\Services\PoleService;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PoleServiceTest extends TestCase
{
    use RefreshDatabase;

    //setting up scenario for all tests
    public function setUp(): void
    {
        parent::setUp();

        Pole::factory()->create(
            [
                'name' => 'Pole Alpha',
            ]
        );

        Pole::factory()->create(
            [
                'name' => 'Pole Beta',
            ]
        );

        $this->service = new PoleService;
    }

    /**
     * @test
     */
    public function polesShouldBeListed()
    {
        //verifications
        $this->assertEquals('Pole Alpha', $this->service->list()->first()->name);
        $this->assertEquals(2, $this->service->list()->count());
    }

    /**
     * @test
     */
    public function poleShouldBeCreated()
    {
        //setting up scenario
        $attributes = array();

        $attributes['name'] = 'Pole Gama';
        $attributes['description'] = '3rd Pole';

        //execution 
        $this->service->create($attributes);

        //verifications
        $this->assertEquals('Pole Gama', Pole::find(3)->name);
        $this->assertEquals(3, Pole::all()->count());
    }

    /**
     * @test
     */
    public function poleShouldBeUpdated()
    {
        //setting up scenario

        $course = Pole::find(1);

        $attributes = array();

        $attributes['name'] = 'Pole Delta';
        $attributes['description'] = 'New 1st Pole';

        //execution
        $this->service->update($attributes, $course);

        //verifications
        $this->assertEquals('Pole Delta', Pole::find(1)->name);
        $this->assertEquals('New 1st Pole', Pole::find(1)->description);
        $this->assertEquals(2, Pole::all()->count());
    }

    /**
     * @test
     */
    public function poleShouldBeDeleted()
    {
        //setting up scenario
        $course = Pole::find(1);

        //execution 
        $this->service->delete($course);

        //verifications
        $this->assertEquals('Pole Beta', $this->service->list()->first()->name);
        $this->assertEquals(1, $this->service->list()->count());
    }
}
