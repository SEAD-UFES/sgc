<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * Ensures a guest (unauthenticated user) sees the login form
     *
     * @return void
     *
     * @test
     */
    public function guestShouldAccessLoginPage()
    {
        $this->get(route('auth.login'))
            ->assertSuccessful()
            ->assertViewIs('login')
            ->assertSee('email')
            ->assertSee('password')
            ->assertSee('submit');
    }

    /**
     * A guest must be redirected from the root '/' to the login route
     *
     * @return void
     *
     * @test
     */
    public function guestIsRedirectedFromRootLogin()
    {
        $this->get(route('root'))
            ->assertRedirect(route('auth.login'));
    }

    /**
     * A guest must not be able to access the home route
     *
     * @return void
     *
     * @test
     */
    public function guestShouldntSeeHome()
    {
        $this->get(route('home'))
            ->assertStatus(401);
    }

    /**
     * A logged user must be redirected from the login page to
     * the 'home'.
     * A logged user must be redirected from 'root' to the 'home'.
     *
     * @return void
     *
     * @test
     */
    public function authenticatedUserShouldntAccessLoginPage()
    {
        $user = User::factory()->make();

        // it must redirect from / to /home
        $this->actingAs($user)->get(route('root'))
            ->assertRedirect(route('home'));

        // it must redirect from /login to /home
        $this->actingAs($user)->get(route('auth.login'))
            ->assertRedirect(route('home'));
    }

    /**
     * An existing user cannot login with the wrong password
     *
     * @return void
     *
     * @test
     */
    public function userShouldntAuthenticateWithWrongPassword()
    {
        $user = User::factory()->make();

        $this->post(route('auth.login'), [
            'email' => $user->email,
            'password' => Hash::make('wrong-password'),
        ])->assertStatus(401);
    }
}
