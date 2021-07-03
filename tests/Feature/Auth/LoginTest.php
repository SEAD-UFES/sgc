<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_view_a_login_form()
    {
        $response = $this->get('/');

        $response->assertSuccessful();

        $response->assertViewIs('login');

        $response->assertSee('email');
        $response->assertSee('password');
        $response->assertSee('submit');
    }
}
