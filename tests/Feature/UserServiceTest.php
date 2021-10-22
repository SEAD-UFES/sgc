<?php

namespace Tests\Feature;

use App\Models\Employee;
use Tests\TestCase;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

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

        $this->service = new UserService;
    }

    /**
     * @test
     */
    public function usersShouldBeListed()
    {
        //verifications
        $this->assertEquals('johndoe@test1.com', $this->service->list()->first()->email);
        $this->assertEquals(2, $this->service->list()->count());
    }

    /**
     * @test
     */
    public function userShouldBeCreated()
    {
        //setting up scenario
        $attributes = array();
        
        $attributes['email'] = 'bobdoe@test3.com';
        $attributes['password'] = 'password3';
        $attributes['active'] = true;

        //execution 
        $this->service->create($attributes);

        //verifications
        $this->assertEquals('bobdoe@test3.com', User::find(3)->email);
        $this->assertEquals(3, User::all()->count());
    }

    /**
     * @test
     */
    public function userShouldBeUpdated()
    {
        //setting up scenario

        $user = User::find(1);

        $attributes = array();

        $attributes['email'] = 'marydoe@test4.com';
        $attributes['password'] = 'password4';

        //execution
        $this->service->update($attributes, $user);

        //verifications
        $this->assertEquals('marydoe@test4.com', User::find(1)->email);
        $this->assertTrue(Hash::check('password4', User::find(1)->password));
        $this->assertEquals(0, User::find(1)->active);
        $this->assertEquals(2, User::all()->count());
    }

    /**
     * @test
     */
    public function userShouldBeUpdatedExceptPassword()
    {
        //setting up scenario

        $user = User::find(1);

        $attributes = array();

        $attributes['email'] = 'billdoe@test5.com';
        $attributes['password'] = '';
        $attributes['active'] = true;

        //execution
        $this->service->update($attributes, $user);

        //verifications
        $this->assertEquals('billdoe@test5.com', User::find(1)->email);
        $this->assertTrue(Hash::check('password1', User::find(1)->password));
        $this->assertEquals(true, User::find(1)->active);
        $this->assertEquals(2, User::all()->count());
    }

    /**
     * @test
     */
    public function userShouldHaveEmployeeAtached()
    {
        //setting up scenario

        Employee::factory()->create(['email' => 'marydoe@test4.com', 'name' => 'Mary Doe']);

        $user = User::find(1);

        $attributes = array();

        $attributes['email'] = 'marydoe@test4.com';

        //execution
        $this->service->update($attributes, $user);

        //verifications
        $this->assertEquals('marydoe@test4.com', User::find(1)->email);
        $this->assertEquals('Mary Doe', User::find(1)->employee->name);
        $this->assertEquals(2, User::all()->count());
    }

    /**
     * @test
     */
    public function userShouldBeDeleted()
    {
        //setting up scenario
        $user = User::find(1);

        //execution 
        $this->service->delete($user);

        //verifications
        $this->assertEquals('janedoe@test2.com', $this->service->list()->first()->email);
        $this->assertEquals(1, $this->service->list()->count());
    }
}
