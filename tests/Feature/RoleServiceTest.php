<?php

namespace Tests\Feature;

use App\Enums\GrantTypes;
use App\Events\ModelListed;
use App\Events\ModelRead;
use App\Models\Role;
use App\Services\Dto\RoleDto;
use App\Services\RoleService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class RoleServiceTest extends TestCase
{
    use RefreshDatabase;

    //setting up scenario for all tests
    protected function setUp(): void
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

        $this->service = new RoleService();
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
            Event::assertDispatched(ModelListed::class);
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
            Event::assertDispatched(ModelRead::class);
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
        $attributes = [];

        $attributes['name'] = 'Role Gama';
        $attributes['description'] = '3rd Role';
        $attributes['grantValue'] = 123456;
        $attributes['grantType'] = GrantTypes::P;

        $dto = new RoleDto(...$attributes);

        Event::fakeFor(function () use ($dto) {
            //execution
            $this->service->create($dto);

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

        $attributes = [];

        $attributes['name'] = 'Role Delta';
        $attributes['description'] = 'New 1st Role';
        $attributes['grantValue'] = 123456;
        $attributes['grantType'] = GrantTypes::P;

        $dto = new RoleDto(...$attributes);

        Event::fakeFor(function () use ($dto, $role) {
            //execution
            $this->service->update($dto, $role);

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
