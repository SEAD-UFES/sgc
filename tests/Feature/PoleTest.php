<?php

namespace Tests\Feature;

use App\Models\Pole;
use App\Models\User;
use App\Models\UserType;
use App\Models\Responsibility;
use App\Repositories\ResponsibilityRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PoleTest extends TestCase
{
    use RefreshDatabase;

    protected $poleData = ['name' => 'Polo Teste', 'description' => 'Teste.']; //Pole for legacy tests

    private static User $userAdm;
    private static User $userDir;
    private static User $userAss;
    private static User $userSec;
    private static User $userCoord;
    private static User $userLdi;

    private ResponsibilityRepository $responsibilityRepository;

    public function __construct()
    {
        parent::__construct('PoleTest');

        $this->responsibilityRepository = new ResponsibilityRepository();
    }

    public function setUp(): void
    {
        parent::setUp();

        self::$userAdm = User::factory()->create();
        $userTypeAdm = UserType::factory()->admin()->create();
        Responsibility::factory()->create([
            'user_id' => self::$userAdm->id,
            'user_type_id' => $userTypeAdm->id,
            'course_id' => null,
        ]);

        self::$userDir = User::factory()->create();
        $userTypeDir = UserType::factory()->director()->create();
        Responsibility::factory()->create([
            'user_id' => self::$userDir->id,
            'user_type_id' => $userTypeDir->id,
            'course_id' => null,
        ]);

        self::$userAss = User::factory()->create();
        $userTypeAss = UserType::factory()->assistant()->create();
        Responsibility::factory()->create([
            'user_id' => self::$userAss->id,
            'user_type_id' => $userTypeAss->id,
            'course_id' => null,
        ]);

        self::$userSec = User::factory()->create();
        $userTypeSec = UserType::factory()->secretary()->create();
        Responsibility::factory()->create([
            'user_id' => self::$userSec->id,
            'user_type_id' => $userTypeSec->id,
            'course_id' => null,
        ]);

        self::$userCoord = User::factory()->create();
        $userTypeCoord = UserType::factory()->coordinator()->create();
        Responsibility::factory()->create([
            'user_id' => self::$userCoord->id,
            'user_type_id' => $userTypeCoord->id,
            'course_id' => null,
        ]);

        self::$userLdi = User::factory()->create();
        $userTypeLdi = UserType::factory()->ldi()->create();
        Responsibility::factory()->create([
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
     * Guest cannot list poles
     *
     * @return void
     */
    public function guestShouldntListPoles()
    {
        $this->get(route('poles.index'))
            ->assertStatus(401);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     *
     * @test
     */
    public function administratorShouldListPoles()
    {
        $this->actingAs(self::$userAdm);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        $this->get('/poles')
            ->assertSee(['Pole Alpha', 'Pole Beta'])
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     *
     * @test
     */
    public function directorShouldListPoles()
    {
        $this->actingAs(self::$userDir);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        $this->get('/poles')
            ->assertSee(['Pole Alpha', 'Pole Beta'])
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     *
     * @test
     */
    public function assistantShouldListPoles()
    {
        $this->actingAs(self::$userAss);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        $this->get('/poles')
            ->assertSee(['Pole Alpha', 'Pole Beta'])
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     *
     * @test
     */
    public function secretaryShouldListPoles()
    {
        $this->actingAs(self::$userSec);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        $this->get('/poles')
            ->assertSee(['Pole Alpha', 'Pole Beta'])
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     *
     * @test
     */
    public function ldiShouldntListPoles()
    {
        $this->actingAs(self::$userLdi);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        $this->get('/poles')
            ->assertStatus(403);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     *
     * @test
     */
    public function coordinatorShouldListPoles()
    {
        $this->actingAs(self::$userCoord);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        $this->get('/poles')
            ->assertSee(['Pole Alpha', 'Pole Beta'])
            ->assertStatus(200);
    }

    // == Legacy tests ==

    /**
     * Guest cannot create a pole
     *
     * @return void
     */
    public function guestShouldntCreatePole()
    {
        $this->post(route('poles.store'), $this->poleData)
            ->assertStatus(401);
    }

    /**
     * Guest cannot update a pole
     *
     * @return void
     */
    public function guestShouldntUpdatePole()
    {
        $pole = $this->getTestPole();

        $this->put(route('poles.update', $pole->id), $this->poleData)
            ->assertStatus(401);
    }

    /**
     * Guest cannot delete a pole
     *
     * @return void
     */
    public function guestShouldntDeletePole()
    {
        $pole = $this->getTestPole();

        $this->get(route('poles.destroy', $pole->id))
            ->assertStatus(401);
    }

    /**
     * Guest cannot access pole create page
     *
     * @return void
     */
    public function guestShouldntAccessCreatePolePage()
    {
        $this->get(route('poles.create'))
            ->assertStatus(401);
    }

    /**
     * Guest cannot access pole edit page
     *
     * @return void
     */
    public function guestShouldntAccessEditPolePage()
    {
        $pole = $this->getTestPole();

        $this->get(route('poles.edit', $pole->id))
            ->assertStatus(401);
    }

    /**
     * Authenticated user can list poles
     *
     * @return void
     */
    public function testAuthenticatedUserWhitoutPermissionAssignmentCannotListPoles()
    {
        $session = $this->getAuthenticatedSession();

        $session->get(route('poles.index'))
            ->assertSee('Proibido');
    }

    /**
     * Authenticated user can create pole
     *
     * @return void
     */
    public function testAuthenticatedUserWithoutPermissionAssignmentCannotCreatePole()
    {
        $session = $this->getAuthenticatedSession();

        $session->post(route('poles.store'), $this->poleData)
            ->assertSee('Proibido');
    }

    /**
     * Authenticated user can update pole
     *
     * @return void
     */
    public function testAuthenticatedUserWithoutPermissionAssignmentCannotUpdatePole()
    {
        $session = $this->getAuthenticatedSession();

        $pole = $this->getTestPole();

        $session->put(
            route('poles.update', $pole->id),
            ['name' => 'updated', 'description' => 'updated']
        )
            ->assertSee('Proibido');
    }

    /**
     * Authenticated user can delete pole
     *
     * @return void
     */
    public function testAuthenticatedUserWithoutPermissionAssignmentCannotDeletePole()
    {
        $session = $this->getAuthenticatedSession();

        $pole = $this->getTestPole();

        $this->assertGreaterThanOrEqual(1, Pole::count());

        $session->delete(route('poles.destroy', $pole->id))
            ->assertSee('Proibido');
    }

    /**
     * Authenticated user can access pole create page
     *
     * @return void
     */
    public function testAuthenticatedUserWithoutPermissionAssignmentCannotAccessCreatePolePage()
    {
        $session = $this->getAuthenticatedSession();

        $session
            ->get(route('poles.create'))
            ->assertSee('Proibido');
    }

    /**
     * Authenticated user can access pole edit page
     *
     * @return void
     */
    public function testAuthenticatedUserWithoutPermissionAssignmentCannotAccessEditPolePage()
    {
        $session = $this->getAuthenticatedSession();

        $pole = $this->getTestPole();

        $session
            ->get(route('poles.edit', $pole->id))
            ->assertSee('Proibido');
    }

    /**
     * Get user with SessionUser attached
     *
     * @return \App\Models\User
     */
    private function getAuthenticatedSession()
    {
        $user = User::factory()->create(['employee_id' => null]);

        return $this->actingAs($user)
            ->withSession(['loggedInUser.currentResponsibilityId' => null]);
    }

    /**
     * Get Pole for testing
     *
     * @return \App\Models\Pole
     */
    private function getTestPole()
    {
        return Pole::factory()->create();
    }
}
