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

  protected $poleData = [ "name" => "Polo Teste", "description" => "Teste." ];

  /**
   * Asserts that guest users cannot access any routes related to the 
   * Pole model. 
   *
   * @return void
   */
  public function test_guest_cannot_perform_crud_operations()
  {
    $this->get(route('poles.index'))
      ->assertRedirect(route('auth.login'));

    $this->post(route('poles.store'), $this->poleData)
      ->assertRedirect(route('auth.login'));

    $this->get(route('poles.create'))
      ->assertRedirect(route('auth.login'));

    // route 'poles.show' is unused. 
    // $this->get(route('poles.show',1))
    //  ->assertRedirect(route('auth.login'));

    $this->put(route('poles.update', 1), $this->poleData)
      ->assertRedirect(route('auth.login'));

    $this->get(route('poles.destroy', 1))
      ->assertRedirect(route('auth.login'));

    $this->get(route('poles.edit', 1))
      ->assertRedirect(route('auth.login'));     
  }

  /**
   * Asserts an authenticated user can perform all CRUD operations
   * in the routes related to the Pole model. 
   *
   * @return void
   */
  public function test_authenticated_user_can_perform_crud_operations()
  {
    $user = User::factory()->make([ "employee_id" => null ]);

    $session = $this->actingAs($user)
      ->withSession([ 'sessionUser' => new SessionUser($user) ]);


    // asser user can access index page for item 
    $session->get(route('poles.index'))
      ->assertSee('Listar Polos');


    // assert table is unpopulated before tests
    $this->assertEquals(Pole::count(), 0);

    $session->post(route('poles.store'), $this->poleData)
      ->assertStatus(302);


    // assert element was persisted
    $this->assertEquals(Pole::count(), 1);

    $pole = Pole::first();

    $this->assertEquals($pole->name, $this->poleData["name"]);
    $this->assertEquals($pole->description, $this->poleData["description"]);


    // assert user can see create page
    $session
      ->get(route('poles.create'))
      ->assertOk();


    // route 'poles.show' is unused. 
    // $session
    //  ->get(route('poles.show',1))
    //  ->assertRedirect(route('auth.login'));


    // assert user can see edit page
    $session
      ->get(route('poles.edit', 1))
      ->assertSee($this->poleData["name"])
      ->assertSee($this->poleData["description"]);


    // assert user can update existing item
    $session
      ->put(route('poles.update', 1), [ "name" => "updated", "description" => "updated" ])
      ->assertStatus(302);

    $pole = Pole::first();

    $this->assertEquals($pole->name, "updated");
    $this->assertEquals($pole->description, "updated");


    // assert usuer can destroy/delete/remove item
    $session
      ->delete(route('poles.destroy', 1))
      ->assertStatus(302);

    $this->assertEquals(Pole::count(), 0);

  }
}
