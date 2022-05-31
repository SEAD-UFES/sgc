<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Role;
use App\Services\RoleService;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;

class RoleServiceTest extends TestCase
{
    use RefreshDatabase;

    //setting up scenario for all tests
    public function setUp(): void
    {
        parent::setUp();

        Role::factory()->create(
            [
                'name' => 'Role Alpha',
            ]
        );

        Role::factory()->create(
            [
                'name' => 'Role Beta',
            ]
        );

        $this->service = new RoleService;
    }

    /**
     * @test
     */
    public function rolesShouldBeListed()
    {
        Event::fakeFor(function () {
            //execution
            $roles = $this->service->list();

            //verifications
            $this->assertEquals('Role Alpha', $roles->first()->name);
            $this->assertCount(2, $roles);
        });
    }

    /**
     * @test
     */
    public function roleShouldBeRetrieved()
    {
        //setting up scenario
        $role = Role::find(1);

        Event::fakeFor(function () use ($role) {
            //execution
            $role = $this->service->read($role);

            //verifications
            Event::assertDispatched('eloquent.fetched: ' . Role::class);
            $this->assertEquals('Role Alpha', $role->name);
            $this->assertCount(2, Role::all());
        });
    }

    /**
     * @test
     */
    public function roleShouldBeCreated()
    {
        //setting up scenario
        $attributes = array();

        $attributes['name'] = 'Role Gama';
        $attributes['description'] = '3rd Role';
        $attributes['grant_value'] = 123456;
        $attributes['grant_type_id'] = 1;

        Event::fakeFor(function () use ($attributes) {
            //execution
            $this->service->create($attributes);

            //verifications
            Event::assertDispatched('eloquent.created: ' . Role::class);
            $this->assertEquals('Role Gama', Role::find(3)->name);
            $this->assertCount(3, Role::all());
        });
    }

    /**
     * @test
     */
    public function roleShouldBeUpdated()
    {
        //setting up scenario

        $role = Role::find(1);

        $attributes = array();

        $attributes['name'] = 'Role Delta';
        $attributes['description'] = 'New 1st Role';

        Event::fakeFor(function () use ($attributes, $role) {
            //execution
            $this->service->update($attributes, $role);

            //verifications
            Event::assertDispatched('eloquent.updated: ' . Role::class);
            $this->assertEquals('Role Delta', Role::find(1)->name);
            $this->assertEquals('New 1st Role', Role::find(1)->description);
            $this->assertCount(2, Role::all());
        });
    }

    /**
     * @test
     */
    public function roleShouldBeDeleted()
    {
        //setting up scenario
        $role = Role::find(1);

        Event::fakeFor(function () use ($role) {
            //execution
            $this->service->delete($role);

            //verifications
            Event::assertDispatched('eloquent.deleted: ' . Role::class);
            $this->assertEquals('Role Beta', $this->service->list()->first()->name);
            $this->assertCount(1, Role::all());
        });
    }
}
