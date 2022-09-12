<?php

namespace Tests\Feature;

use App\Events\ModelListed;
use App\Events\ModelRead;
use App\Models\Employee;
use App\Models\User;
use App\Services\Dto\StoreUserDto;
use App\Services\Dto\UpdateUserDto;
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
            Event::assertDispatched(ModelListed::class);
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
            Event::assertDispatched(ModelRead::class);
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

        $dto = new StoreUserDto($attributes);

        Event::fakeFor(function () use ($dto) {
            //execution
            $this->service->create($dto);

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
        $attributes['active'] = false;

        $dto = new UpdateUserDto($attributes);

        Event::fakeFor(function () use ($user, $dto) {
            //execution
            $this->service->update($dto, $user);

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

        $dto = new UpdateUserDto($attributes);

        Event::fakeFor(function () use ($user, $dto) {
            //execution
            $this->service->update($dto, $user);


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
    public function userShouldHaveEmployeeAttached()
    {
        //setting up scenario
        /**
         * @var User $user
         */
        $user = User::factory()->createOne(['email' => 'marydoe@test4.com', 'employee_id' => null]);
        /**
         * @var Employee $employee
         */
        $employee = Employee::factory()->createOne(['email' => 'marydoe@test4.com', 'name' => 'Mary Doe']);

        $attributes = [];

        $attributes['email'] = 'userShouldHaveEmployeeAtached@test4.com';
        $attributes['employeeId'] = Employee::orderBy('id', 'desc')->first()->id;
        $attributes['password'] = '';
        $attributes['active'] = true;

        $dto = new UpdateUserDto($attributes);

        Event::fakeFor(function () use ($user, $attributes, $dto) {
            //execution
            $this->service->update($dto, $user);

            //verifications
            Event::assertDispatched('eloquent.updating: ' . User::class);
            $this->assertContains('Mary Doe', Employee::pluck('name')->toArray());
            $this->assertEquals($attributes['employeeId'], $user->employee_id);
            $this->assertCount(3, User::all());
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
