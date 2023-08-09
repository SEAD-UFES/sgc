<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use App\Events\ModelListed;
use App\Events\ModelRead;
use App\Models\Employee;
use App\Models\User;
use App\Services\Dto\UserDto;
use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserServiceTest extends TestCase
{
    use RefreshDatabase;

    //setting up scenario for all tests
    protected function setUp(): void
    {
        parent::setUp();

        User::factory()->create(
            [
                'login' => 'johndoe@test1.com',
                'password' => Hash::make('password1'),
                'active' => true,
            ]
        );

        User::factory()->create(
            [
                'login' => 'janedoe@test2.com',
                'password' => Hash::make('password2'),
            ]
        );

        $this->service = new UserService();
    }

    #[Test]
    public function usersShouldBeListed()
    {
        Event::fakeFor(function () {
            //execution
            $users = $this->service->list();

            //verifications
            Event::assertDispatched(ModelListed::class);
            $this->assertContains('johndoe@test1.com', $users->pluck('login')->toArray());
            $this->assertContains('janedoe@test2.com', $users->pluck('login')->toArray());
            $this->assertCount(2, $users);
        });
    }

    #[Test]
    public function userShouldBeRetrieved()
    {
        //setting up scenario
        $user = User::find(1);

        Event::fakeFor(function () use ($user) {
            //execution
            $user = $this->service->read($user);

            //verifications
            Event::assertDispatched(ModelRead::class);
            $this->assertEquals('johndoe@test1.com', $user->login);
            $this->assertCount(2, User::all());
        });
    }

    #[Test]
    public function userShouldBeCreated()
    {
        //setting up scenario
        $attributes = [];

        $attributes['login'] = 'bobdoe@test3.com';
        $attributes['password'] = 'password3';
        $attributes['active'] = true;
        $attributes['employeeId'] = null;

        $dto = new UserDto(...$attributes);

        Event::fakeFor(function () use ($dto) {
            //execution
            $this->service->create($dto);

            //verifications
            Event::assertDispatched('eloquent.creating: ' . User::class);
            $this->assertEquals('bobdoe@test3.com', User::find(3)->login);
            $this->assertCount(3, User::all());
        });
    }

    #[Test]
    public function userShouldBeUpdated()
    {
        //setting up scenario

        $user = User::find(1);

        $attributes = [];

        $attributes['login'] = 'marydoe@test4.com';
        $attributes['password'] = 'password4';
        $attributes['active'] = false;
        $attributes['employeeId'] = null;

        $dto = new UserDto(...$attributes);

        Event::fakeFor(function () use ($user, $dto) {
            //execution
            $this->service->update($dto, $user);

            //verifications
            Event::assertDispatched('eloquent.updating: ' . User::class);
            $this->assertEquals('marydoe@test4.com', User::find(1)->login);
            $this->assertTrue(Hash::check('password4', User::find(1)->password));
            $this->assertEquals(0, User::find(1)->active);
            $this->assertCount(2, User::all());
        });
    }

    #[Test]
    public function userShouldBeUpdatedExceptPassword()
    {
        //setting up scenario

        $user = User::find(1);

        $attributes = [];

        $attributes['login'] = 'billdoe@test5.com';
        $attributes['password'] = '';
        $attributes['active'] = true;
        $attributes['employeeId'] = null;

        $dto = new UserDto(...$attributes);

        Event::fakeFor(function () use ($user, $dto) {
            //execution
            $this->service->update($dto, $user);


            //verifications
            Event::assertDispatched('eloquent.updating: ' . User::class);
            $this->assertEquals('billdoe@test5.com', User::find(1)->login);
            $this->assertTrue(Hash::check('password1', User::find(1)->password));
            $this->assertEquals(true, User::find(1)->active);
            $this->assertCount(2, User::all());
        });
    }

    #[Test]
    public function userShouldHaveEmployeeAttached()
    {
        //setting up scenario
        /**
         * @var User $user
         */
        $user = User::factory()->createOne(['login' => 'marydoe@test4.com', 'employee_id' => null]);
        /**
         * @var Employee $employee
         */
        $employee = Employee::factory()->createOne(['email' => 'marydoe@test4.com', 'name' => 'Mary Doe']);

        $attributes = [];

        $attributes['login'] = 'userShouldHaveEmployeeAtached@test4.com';
        $attributes['employeeId'] = Employee::orderBy('id', 'desc')->first()->id;
        $attributes['password'] = '';
        $attributes['active'] = true;

        $dto = new UserDto(...$attributes);

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

    #[Test]
    public function userShouldBeDeleted()
    {
        //setting up scenario
        $user = User::find(1);

        Event::fakeFor(function () use ($user) {
            //execution
            $this->service->delete($user);

            //verifications
            Event::assertDispatched('eloquent.deleting: ' . User::class);
            $this->assertEquals('janedoe@test2.com', $this->service->list()->first()->login);
            $this->assertCount(1, User::all());
        });
    }
}
