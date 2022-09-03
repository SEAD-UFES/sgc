<?php

namespace Tests\Feature;

use App\Events\ModelListed;
use App\Events\ModelRead;
use App\Models\Pole;
use App\Services\PoleService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

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

        $this->service = new PoleService();
    }

    /**
     * @test
     */
    public function polesShouldBeListed()
    {
        Event::fakeFor(function () {
            //execution
            $poles = $this->service->list();

            //verifications
            Event::assertDispatched(ModelListed::class);
            $this->assertEquals('Pole Alpha', $this->service->list()->first()->name);
            $this->assertCount(2, $poles);
        });
    }

    /**
     * @test
     */
    public function poleShouldBeRetrieved()
    {
        //setting up scenario
        $pole = Pole::find(1);

        Event::fakeFor(function () use ($pole) {
            //execution
            $pole = $this->service->read($pole);

            //verifications
            Event::assertDispatched(ModelRead::class);
            $this->assertEquals('Pole Alpha', $pole->name);
            $this->assertCount(2, Pole::all());
        });
    }

    /**
     * @test
     */
    public function poleShouldBeCreated()
    {
        //setting up scenario
        $attributes = [];

        $attributes['name'] = 'Pole Gama';
        $attributes['description'] = '3rd Pole';

        Event::fakeFor(function () use ($attributes) {
            //execution
            $this->service->create($attributes);

            //verifications
            Event::assertDispatched('eloquent.created: ' . Pole::class);
            $this->assertEquals('Pole Gama', Pole::find(3)->name);
            $this->assertCount(3, Pole::all());
        });
    }

    /**
     * @test
     */
    public function poleShouldBeUpdated()
    {
        //setting up scenario

        $pole = Pole::find(1);

        $attributes = [];

        $attributes['name'] = 'Pole Delta';
        $attributes['description'] = 'New 1st Pole';

        Event::fakeFor(function () use ($attributes, $pole) {
            //execution
            $this->service->update($attributes, $pole);

            //verifications
            Event::assertDispatched('eloquent.updated: ' . Pole::class);
            $this->assertEquals('Pole Delta', Pole::find(1)->name);
            $this->assertEquals('New 1st Pole', Pole::find(1)->description);
            $this->assertCount(2, Pole::all());
        });
    }

    /**
     * @test
     */
    public function poleShouldBeDeleted()
    {
        //setting up scenario
        $pole = Pole::find(1);

        Event::fakeFor(function () use ($pole) {
            //execution
            $this->service->delete($pole);

            //verifications
            Event::assertDispatched('eloquent.deleted: ' . Pole::class);
            $this->assertEquals('Pole Beta', $this->service->list()->first()->name);
            $this->assertCount(1, Pole::all());
        });
    }
}
