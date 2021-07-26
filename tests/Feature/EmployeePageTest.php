<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

use App\Models\User;

class EmployeePage extends TestCase
{
    use DatabaseMigrations;

    /**
     * Asserts guest user cannot view employee page
     *
     * @return void
     */

    public function test_guest_cannot_view_employee_page()
    {
        $response = $this->get(route('employee'));

        $response->assertRedirect(route('auth.login'));
    }

    /**
     * Asserts authenticated user can see the employee page
     *
     * @return void
     */
    public function test_user_sees_employee_page()
    {
        $user = User::factory()->make();

        $response = $this->actingAs($user)->get(route('employee'));

        $response->assertSee('Colaboradores');
    }
}
