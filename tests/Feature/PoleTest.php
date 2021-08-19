<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

use App\Models\User;
use App\Models\Pole;

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
     * Route `poles.show` is not used at the moment.
     *
     * Guest cannot access pole details page
     * @return void
   public function test_guest_cannot_see_pole_details()
   {
     $this->get(route('poles.show',1))
       ->assertRedirect(route('auth.login'));
   }
     */

    /**
     * Authenticated user can list poles
     * @return void
     */
    public function test_authenticated_user_can_list_poles()
    {
        $session = $this->getAuthenticatedSession();

        $session->get(route('poles.index'))
            ->assertSee('Listar Polos');
    }

    /**
     * Authenticated user can create pole
     * @return void
     */
    public function test_authenticated_user_can_create_pole()
    {
        $session = $this->getAuthenticatedSession();

        $this->assertEquals(Pole::count(), 0);

        $session->post(route('poles.store'), $this->poleData)
            ->assertStatus(302);

        $this->assertEquals(Pole::count(), 1);

        $pole = Pole::first();

        $this->assertEquals($pole->name, $this->poleData["name"]);
        $this->assertEquals($pole->description, $this->poleData["description"]);
    }

    /**
     * Authenticated user can update pole
     * @return void
     */
    public function test_authenticated_user_can_update_pole()
    {
        $session = $this->getAuthenticatedSession();

        $pole = $this->getTestPole();

        $session
            ->put(
                route('poles.update', $pole->id),
                ["name" => "updated", "description" => "updated"]
            )
            ->assertStatus(302);

        $updatedPole = Pole::find($pole->id);

        $this->assertEquals($updatedPole->name, "updated");
        $this->assertEquals($updatedPole->description, "updated");
    }

    /**
     * Authenticated user can delete pole
     * @return void
     */
    public function test_authenticated_user_can_delete_pole()
    {
        $session = $this->getAuthenticatedSession();

        $pole = $this->getTestPole();

        $this->assertEquals(Pole::count(), 1);

        $session
            ->delete(route('poles.destroy', $pole->id))
            ->assertStatus(302);

        $this->assertEquals(Pole::count(), 0);
    }

    /**
     * Authenticated user can access pole create page
     * @return void
     */
    public function test_authenticated_user_can_access_create_pole_page()
    {
        $session = $this->getAuthenticatedSession();

        $session
            ->get(route('poles.create'))
            ->assertOk();
    }

    /**
     * Authenticated user can access pole edit page
     * @return void
     */
    public function test_authenticated_user_can_access_edit_pole_page()
    {
        $session = $this->getAuthenticatedSession();

        $pole = $this->getTestPole();

        $session
            ->get(route('poles.edit', $pole->id))
            ->assertSee($pole->name)
            ->assertSee($pole->description);
    }


    /**
     * Get user with SessionUser attached
     * @return \App\Models\User
     */
    private function getAuthenticatedSession()
    {
        $user  = User::factory()->make(["employee_id" => null]);

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
