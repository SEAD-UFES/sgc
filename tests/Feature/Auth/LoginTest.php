<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

use App\Models\User;

class LoginTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * Ensures a guest (unauthenticated user) sees the login form
     *
     * @return void
     */
    public function testGuestCanViewALoginForm()
    {
        $response = $this->get(route('auth.login'));

        $response->assertSuccessful();

        $response->assertViewIs('login');

        $response->assertSee('email');
        $response->assertSee('password');
        $response->assertSee('submit');
    }

    /**
     * A guest must be redirected from the root '/' to the login route
     *
     * @return void
     */
    public function testGuestIsRedirectedFromRootLogin()
    {
        $response = $this->get(route('root'));

        $response->assertRedirect(route('auth.login'));
    }

    /**
     * A guest must not be able to access the home route
     *
     * @return void
     */
    public function testGuestCannotSeeHome()
    {
        $response = $this->get(route('home'));

        $response->assertRedirect(route('auth.login'));
    }

    /**
     * A logged user must be redirected from the login page to
     * the 'home'.
     * A logged user must be redirected from 'root' to the 'home'.
     *
     * @return void
     */
    public function testUserCannotViewLoginFormAuthenticated()
    {
        $user = User::factory()->make();

        // it must redirect from / to /home
        $response = $this->actingAs($user)->get(route('root'));
        $response->assertRedirect(route('home'));

        // it must redirect from /login to /home
        $response = $this->actingAs($user)->get(route('auth.login'));
        $response->assertRedirect(route('home'));
    }

    /**
     * An existing user cannot login with the wrong password
     *
     * @return void
     */
    public function testUserCannotAuthenticateWithWrongPassword()
    {
        $user = User::factory()->make();

        $response = $this->post(route('auth.login'), [
            'email' => $user->email,
            'password' =>  Hash::make('wrong-password'),
        ]);

        $response->assertStatus(302);
    }
}
