<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Role;
use App\Services\RoleService;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

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
        //verifications
        $this->assertEquals('Role Alpha', $this->service->list()->first()->name);
        $this->assertEquals(2, $this->service->list()->count());
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
        $attributes['grant_value'] = 1234.56;
        $attributes['grant_type_id'] = 1;

        //execution 
        $this->service->create($attributes);

        //verifications
        $this->assertEquals('Role Gama', Role::find(3)->name);
        $this->assertEquals(3, Role::all()->count());
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

        //execution
        $this->service->update($attributes, $role);

        //verifications
        $this->assertEquals('Role Delta', Role::find(1)->name);
        $this->assertEquals('New 1st Role', Role::find(1)->description);
        $this->assertEquals(2, Role::all()->count());
    }

    /**
     * @test
     */
    public function roleShouldBeDeleted()
    {
        //setting up scenario
        $role = Role::find(1);

        //execution 
        $this->service->delete($role);

        //verifications
        $this->assertEquals('Role Beta', $this->service->list()->first()->name);
        $this->assertEquals(1, $this->service->list()->count());
    }
}
