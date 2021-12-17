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
    public function testGuestCannotListPoles()
    {
        $this->get(route('poles.index'))
            ->assertRedirect(route('auth.login'));
    }
    /**
     * Guest cannot create a pole
     * @return void
     */
    public function testGuestCannotCreatePole()
    {
        $this->post(route('poles.store'), $this->poleData)
            ->assertRedirect(route('auth.login'));
    }

    /**
     * Guest cannot update a pole
     * @return void
     */
    public function testGuestCannotUpdatePole()
    {
        $pole = $this->getTestPole();

        $this->put(route('poles.update', $pole->id), $this->poleData)
            ->assertRedirect(route('auth.login'));
    }

    /**
     * Guest cannot delete a pole
     * @return void
     */
    public function testGuestCannotDeletePole()
    {
        $pole = $this->getTestPole();

        $this->get(route('poles.destroy', $pole->id))
            ->assertRedirect(route('auth.login'));
    }

    /**
     * Guest cannot access pole create page
     * @return void
     */
    public function testGuestCannotAccessCreatePolePage()
    {
        $this->get(route('poles.create'))
            ->assertRedirect(route('auth.login'));
    }

    /**
     * Guest cannot access pole edit page
     * @return void
     */
    public function testGuestCannotAccessEditPolePage()
    {
        $pole = $this->getTestPole();

        $this->get(route('poles.edit', $pole->id))
            ->assertRedirect(route('auth.login'));
    }

    /**
     * Authenticated user can list poles
     * @return void
     */
    public function testAuthenticatedUserWhitoutPermissionAssignmentCannotListPoles()
    {
        $session = $this->getAuthenticatedSession();

        $session->get(route('poles.index'))
            ->assertSee('Acesso negado');
    }

    /**
     * Authenticated user can create pole
     * @return void
     */
    public function testAuthenticatedUserWithoutPermissionAssignmentCannotCreatePole()
    {
        $session = $this->getAuthenticatedSession();

        $session->post(route('poles.store'), $this->poleData)
          ->assertSee('Acesso negado');
    }

    /**
     * Authenticated user can update pole
     * @return void
     */
    public function testAuthenticatedUserWithoutPermissionAssignmentCannotUpdatePole()
    {
        $session = $this->getAuthenticatedSession();

        $pole = $this->getTestPole();

        $session->put(
            route('poles.update', $pole->id),
            ["name" => "updated", "description" => "updated"]
        )
            ->assertSee('Acesso negado');
    }

    /**
     * Authenticated user can delete pole
     * @return void
     */
    public function testAuthenticatedUserWithoutPermissionAssignmentCannotDeletePole()
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
    public function testAuthenticatedUserWithoutPermissionAssignmentCannotAccessCreatePolePage()
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
    public function testAuthenticatedUserWithoutPermissionAssignmentCannotAccessEditPolePage()
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
