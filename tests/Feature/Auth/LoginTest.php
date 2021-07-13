<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Database\Eloquent\Factories;
use Tests\TestCase;

use App\Models\User;

class LoginTest extends TestCase
{
    /**
     * Ensures a guest (unauthenticated user) sees the login form
     *
     * @return void
     */
    public function test_guest_can_view_a_login_form()
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
    public function test_guest_is_redirected_from_root_login()
    {
        $response = $this->get(route('root'));

        $response->assertRedirect(route('auth.login'));
    }

    /**
     * A guest must not be able to access the home route
     *
     * @return void
     */
    public function test_guest_cannot_see_home()
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

    /*
     * TODO:  Fix foreign key constraint error while creating 
     *        test user.
    public function test_user_cannot_view_login_form_authenticated()
    {
        $user = User::factory()->make();

        // it must redirect from / to /home
        $response = $this->actingAs($user)->get(route('root'));
        $response->assertRedirect(route('home'));

        // it must redirect from /login to /home
        $response = $this->actingAs($user)->get(route('auth.login'));
        $response->assertRedirect(route('home'));
    }
     */
}
