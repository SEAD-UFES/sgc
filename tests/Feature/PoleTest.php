<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

use App\Models\User;
use App\Models\Pole;
use App\Models\UserType;
use App\Models\UserTypeAssignment;

use App\CustomClasses\SessionUser;

class PoleTest extends TestCase
{
    use DatabaseMigrations;

    protected $poleData = ["name" => "Polo Teste", "description" => "Teste."];

    /**
     * Guest cannot list poles
     * @return void
     */
    public function test_guest_cannot_list_poles()
    {
        $this->get(route('poles.index'))
            ->assertRedirect(route('auth.login'));
    }
    /**
     * Guest cannot create a pole
     * @return void
     */
    public function test_guest_cannot_create_pole()
    {
        $this->post(route('poles.store'), $this->poleData)
            ->assertRedirect(route('auth.login'));
    }

    /**
     * Guest cannot update a pole
     * @return void
     */
    public function test_guest_cannot_update_pole()
    {
        $pole = $this->getTestPole();

        $this->put(route('poles.update', $pole->id), $this->poleData)
            ->assertRedirect(route('auth.login'));
    }

    /**
     * Guest cannot delete a pole
     * @return void
     */
    public function test_guest_cannot_delete_pole()
    {
        $pole = $this->getTestPole();

        $this->get(route('poles.destroy', $pole->id))
            ->assertRedirect(route('auth.login'));
    }

    /**
     * Guest cannot access pole create page
     * @return void
     */
    public function test_guest_cannot_access_create_pole_page()
    {
        $this->get(route('poles.create'))
            ->assertRedirect(route('auth.login'));
    }

    /**
     * Guest cannot access pole edit page
     * @return void
     */
    public function test_guest_cannot_access_edit_pole_page()
    {
        $pole = $this->getTestPole();

        $this->get(route('poles.edit', $pole->id))
            ->assertRedirect(route('auth.login'));
    }

    /**
     * Authenticated user can list poles
     * @return void
     */
    public function test_authenticated_user_whitout_permission_assignment_cannot_list_poles()
    {
        $session = $this->getAuthenticatedSession();

        $session->get(route('poles.index'))
            ->assertSee('Acesso negado');
    }

    /**
     * Authenticated user can create pole
     * @return void
     */
    public function test_authenticated_user_without_permission_assignment_cannot_create_pole()
    {
        $session = $this->getAuthenticatedSession();

        $session->post(route('poles.store'), $this->poleData)
          ->assertSee('Acesso negado');
    }

    /**
     * Authenticated user can update pole
     * @return void
     */
    public function test_authenticated_user_without_permission_assignment_cannot_update_pole()
    {
        $session = $this->getAuthenticatedSession();

        $pole = $this->getTestPole();

        $session->put(route('poles.update', $pole->id),
                ["name" => "updated", "description" => "updated"])
            ->assertSee('Acesso negado');
    }

    /**
     * Authenticated user can delete pole
     * @return void
     */
    public function test_authenticated_user_without_permission_assignment_cannot_delete_pole()
    {
        $session = $this->getAuthenticatedSession();

        $pole = $this->getTestPole();

        $this->assertEquals(Pole::count(), 1);

        $session
            ->delete(route('poles.destroy', $pole->id))
            ->assertSee('Acesso negado');
    }

    /**
     * Authenticated user can access pole create page
     * @return void
     */
    public function test_authenticated_user_without_permission_assignment_cannot_access_create_pole_page()
    {
        $session = $this->getAuthenticatedSession();

        $session
            ->get(route('poles.create'))
            ->assertSee('Acesso negado');
    }

    /**
     * Authenticated user can access pole edit page
     * @return void
     */
    public function test_authenticated_user_without_permission_assignment_cannot_access_edit_pole_page()
    {
        $session = $this->getAuthenticatedSession();

        $pole = $this->getTestPole();

        $session
            ->get(route('poles.edit', $pole->id))
            ->assertSee('Acesso negado');
    }


    /**
     * Get user with SessionUser attached
     * @return \App\Models\User
     */
    private function getAuthenticatedSession()
    {
        $user  = User::factory()->create(["employee_id" => null]);

        $session = $this->actingAs($user)
            ->withSession(['sessionUser' => new SessionUser($user)]);

        return $session;
    }

    /**
     * Get Pole for testing
     * @return \App\Models\Pole
     */
    private function getTestPole()
    {
        $pole = Pole::factory()->create();

        return $pole;
    }
}
