<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

use App\Models\User;
use App\Models\Pole;
use App\Models\UserType;
use App\Models\UserTypeAssignment;

class PoleTest extends TestCase
{
    use RefreshDatabase;

    protected $poleData = ["name" => "Polo Teste", "description" => "Teste."]; //Pole for legacy tests

    private static $userAdm;
    private static $userDir;
    private static $userAss;
    private static $userSec;
    private static $userCoord;
    private static $userLdi;

    public function setUp(): void
    {
        parent::setUp();

        self::$userAdm = User::factory()->create();
        $userTypeAdm = UserType::factory()->admin()->create();
        UserTypeAssignment::factory()->create([
            'user_id' => self::$userAdm->id,
            'user_type_id' => $userTypeAdm->id,
            'course_id' => null,
        ]);

        self::$userDir = User::factory()->create();
        $userTypeDir = UserType::factory()->director()->create();
        UserTypeAssignment::factory()->create([
            'user_id' => self::$userDir->id,
            'user_type_id' => $userTypeDir->id,
            'course_id' => null,
        ]);

        self::$userAss = User::factory()->create();
        $userTypeAss = UserType::factory()->assistant()->create();
        UserTypeAssignment::factory()->create([
            'user_id' => self::$userAss->id,
            'user_type_id' => $userTypeAss->id,
            'course_id' => null,
        ]);

        self::$userSec = User::factory()->create();
        $userTypeSec = UserType::factory()->secretary()->create();
        UserTypeAssignment::factory()->create([
            'user_id' => self::$userSec->id,
            'user_type_id' => $userTypeSec->id,
            'course_id' => null,
        ]);

        self::$userCoord = User::factory()->create();
        $userTypeCoord = UserType::factory()->coordinator()->create();
        UserTypeAssignment::factory()->create([
            'user_id' => self::$userCoord->id,
            'user_type_id' => $userTypeCoord->id,
            'course_id' => null,
        ]);

        self::$userLdi = User::factory()->create();
        $userTypeLdi = UserType::factory()->ldi()->create();
        UserTypeAssignment::factory()->create([
            'user_id' => self::$userLdi->id,
            'user_type_id' => $userTypeLdi->id,
            'course_id' => null,
        ]);

        Pole::factory()->create(
            [
                'name' => 'Pole Alpha',
            ]
        );

        Pole::factory()->create(
            [
                'name' => 'Pole Beta',
            ]
        );
    }

    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function unloggedUserShouldntSeePoles()
    {
        $response = $this->get('/poles');
        $response->assertRedirect(route('auth.login'));
    }

    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function administratorShouldSeePoles()
    {
        $this->be(self::$userAdm)
            ->withSession(['current_uta' => auth()->user()->getFirstUTA(), 'current_uta_id' => auth()->user()->getFirstUTA()->id]);

        $response = $this->get('/poles');
        $response->assertSee(['Pole Alpha', 'Pole Beta']);
        $response->assertStatus(200);
    }


    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function directorShouldSeePoles()
    {
        $this->be(self::$userDir)
            ->withSession(['current_uta' => auth()->user()->getFirstUTA(), 'current_uta_id' => auth()->user()->getFirstUTA()->id]);

        $response = $this->get('/poles');
        $response->assertSee(['Pole Alpha', 'Pole Beta']);
        $response->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function assistantShouldSeePoles()
    {
        $this->be(self::$userAss)
            ->withSession(['current_uta' => auth()->user()->getFirstUTA(), 'current_uta_id' => auth()->user()->getFirstUTA()->id]);

        $response = $this->get('/poles');
        $response->assertSee(['Pole Alpha', 'Pole Beta']);
        $response->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function secretaryShouldSeePoles()
    {
        $this->be(self::$userSec)
            ->withSession(['current_uta' => auth()->user()->getFirstUTA(), 'current_uta_id' => auth()->user()->getFirstUTA()->id]);

        $response = $this->get('/poles');
        $response->assertSee(['Pole Alpha', 'Pole Beta']);
        $response->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function ldiShouldntSeePoles()
    {
        $this->be(self::$userLdi)
            ->withSession(['current_uta' => auth()->user()->getFirstUTA(), 'current_uta_id' => auth()->user()->getFirstUTA()->id]);

        $response = $this->get('/poles');
        $response->assertStatus(403);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function coordinatorShouldSeePoles()
    {
        $this->be(self::$userCoord)
            ->withSession(['current_uta' => auth()->user()->getFirstUTA(), 'current_uta_id' => auth()->user()->getFirstUTA()->id]);

        $response = $this->get('/poles');
        $response->assertSee(['Pole Alpha', 'Pole Beta']);
        $response->assertStatus(200);
    }


    // == Legacy tests ==

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

        $this->assertGreaterThanOrEqual(1, Pole::count());

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
        $user = User::factory()->create(["employee_id" => null]);

        $session = $this->actingAs($user)
            ->withSession(['current_uta_id' => null, 'current_uta_id' => null]);

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
