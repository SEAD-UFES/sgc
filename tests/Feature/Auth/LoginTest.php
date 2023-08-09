<?php

namespace Tests\Feature\Auth;

use PHPUnit\Framework\Attributes\Test;
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
     */
    #[Test]
    public function guestShouldAccessLoginPage(): void
    {
        $this->get(route('auth.login'))
            ->assertSuccessful()
            ->assertViewIs('login')
            ->assertSee('Login')
            ->assertSee('password')
            ->assertSee('submit');
    }

    /**
     * A guest must be redirected from the root '/' to the login route
     *
     * @return void
     */
    #[Test]
    public function guestIsRedirectedFromRootLogin(): void
    {
        $this->get(route('root'))
            ->assertRedirect(route('auth.login'));
    }

    /**
     * A guest must not be able to access the home route
     *
     * @return void
     */
    #[Test]
    public function guestShouldntSeeHome(): void
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
     */
    #[Test]
    public function authenticatedUserShouldntAccessLoginPage(): void
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
     */
    #[Test]
    public function userShouldntAuthenticateWithWrongPassword(): void
    {
        $user = User::factory()->create(
            [
                'login' => 'mail@domain.com',
            ]
        );

        $this->post(route('auth.login'), [
            'login' => $user->login,
            'password' => Hash::make('wrong-password'),
        ])->assertStatus(401);
    }
}
