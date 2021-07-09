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
     * A basic feature test example.
     *
     * @return void
     */
    public function test_guest_can_view_a_login_form()
    {
        $response = $this->get('/login');

        $response->assertSuccessful();

        $response->assertViewIs('login');

        $response->assertSee('email');
        $response->assertSee('password');
        $response->assertSee('submit');
    }

    public function test_root_redirects_to_login()
    {
        $response = $this->get('/');

        $response->assertStatus(302);

        $response->assertRedirect('/login');
    }

    public function test_user_cannot_view_login_form_authenticated()
    {
        $user = User::factory()->make();

        // it must redirect from / to /home
        $response = $this->actingAs($user)->get('/');
        $response->assertRedirect('/webhome');

        // it must redirect from /login to /home
        $response = $this->actingAs($user)->get('/login');
        $response->assertRedirect('/webhome');
    }

    public function test_guest_cannot_see_home() 
    {
        $response = $this->get('/webhome');

        $response->assertRedirect('/login');
    }
}
