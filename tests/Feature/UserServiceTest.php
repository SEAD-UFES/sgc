<?php

namespace Tests\Feature;

use App\Models\Employee;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserServiceTest extends TestCase
{
    use RefreshDatabase;

    //setting up scenario for all tests
    public function setUp(): void
    {
        parent::setUp();

        User::factory()->create(
            [
                'email' => 'johndoe@test1.com',
                'password' => Hash::make('password1'),
                'active' => true,
            ]
        );

        User::factory()->create(
            [
                'email' => 'janedoe@test2.com',
                'password' => Hash::make('password2'),
            ]
        );

        $this->service = new UserService();
    }

    /**
     * @test
     */
    public function usersShouldBeListed()
    {
        Event::fakeFor(function () {
            //execution
            $users = $this->service->list();

            //verifications
            Event::assertDispatched('eloquent.listed: ' . User::class);
            $this->assertContains('johndoe@test1.com', $users->pluck('email')->toArray());
            $this->assertContains('janedoe@test2.com', $users->pluck('email')->toArray());
            $this->assertCount(2, $users);
        });
    }

    /**
     * @test
     */
    public function userShouldBeRetrieved()
    {
        //setting up scenario
        $user = User::find(1);

        Event::fakeFor(function () use ($user) {
            //execution
            $user = $this->service->read($user);

            //verifications
            Event::assertDispatched('eloquent.fetched: ' . User::class);
            $this->assertEquals('johndoe@test1.com', $user->email);
            $this->assertCount(2, User::all());
        });
    }

    /**
     * @test
     */
    public function userShouldBeCreated()
    {
        //setting up scenario
        $attributes = [];

        $attributes['email'] = 'bobdoe@test3.com';
        $attributes['password'] = 'password3';
        $attributes['active'] = true;

        Event::fakeFor(function () use ($attributes) {
            //execution
            $this->service->create($attributes);

            //verifications
            Event::assertDispatched('eloquent.creating: ' . User::class);
            $this->assertEquals('bobdoe@test3.com', User::find(3)->email);
            $this->assertCount(3, User::all());
        });
    }

    /**
     * @test
     */
    public function userShouldBeUpdated()
    {
        //setting up scenario

        $user = User::find(1);

        $attributes = [];

        $attributes['email'] = 'marydoe@test4.com';
        $attributes['password'] = 'password4';

        Event::fakeFor(function () use ($user, $attributes) {
            //execution
            $this->service->update($attributes, $user);

            //verifications
            Event::assertDispatched('eloquent.updating: ' . User::class);
            $this->assertEquals('marydoe@test4.com', User::find(1)->email);
            $this->assertTrue(Hash::check('password4', User::find(1)->password));
            $this->assertEquals(0, User::find(1)->active);
            $this->assertCount(2, User::all());
        });
    }

    /**
     * @test
     */
    public function userShouldBeUpdatedExceptPassword()
    {
        //setting up scenario

        $user = User::find(1);

        $attributes = [];

        $attributes['email'] = 'billdoe@test5.com';
        $attributes['password'] = '';
        $attributes['active'] = true;

        Event::fakeFor(function () use ($user, $attributes) {
            //execution
            $this->service->update($attributes, $user);

            //verifications
            Event::assertDispatched('eloquent.updating: ' . User::class);
            $this->assertEquals('billdoe@test5.com', User::find(1)->email);
            $this->assertTrue(Hash::check('password1', User::find(1)->password));
            $this->assertEquals(true, User::find(1)->active);
            $this->assertCount(2, User::all());
        });
    }

    /**
     * @test
     */
    public function userShouldHaveEmployeeAtached()
    {
        //setting up scenario

        Employee::factory()->create(['email' => 'marydoe@test4.com', 'name' => 'Mary Doe']);

        $user = User::find(1);

        $attributes = [];

        $attributes['email'] = 'marydoe@test4.com';

        Event::fakeFor(function () use ($user, $attributes) {
            //execution
            $this->service->update($attributes, $user);

            //verifications
            Event::assertDispatched('eloquent.updating: ' . User::class);
            $this->assertEquals('marydoe@test4.com', User::find(1)->email);
            $this->assertEquals('Mary Doe', User::find(1)->employee->name);
            $this->assertCount(2, User::all());
        });
    }

    /**
     * @test
     */
    public function userShouldBeDeleted()
    {
        //setting up scenario
        $user = User::find(1);

        Event::fakeFor(function () use ($user) {
            //execution
            $this->service->delete($user);

            //verifications
            Event::assertDispatched('eloquent.deleting: ' . User::class);
            $this->assertEquals('janedoe@test2.com', $this->service->list()->first()->email);
            $this->assertCount(1, User::all());
        });
    }
}
