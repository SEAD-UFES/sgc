<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use App\Models\Role;
use App\Models\User;
use App\Models\UserType;
use App\Models\Responsibility;
use App\Repositories\ResponsibilityRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleTest extends TestCase
{
    use RefreshDatabase;

    private static User $userAdm;
    private static User $userDir;
    private static User $userAss;
    private static User $userSec;
    private static User $userCoord;
    private static User $userLdi;

    private ResponsibilityRepository $responsibilityRepository;

    public function __construct()
    {
        parent::__construct('RoleTest');

        $this->responsibilityRepository = new ResponsibilityRepository();
    }

    protected function setUp(): void
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

        Role::factory()->create(
            [
                'name' => 'Role Alpha',
            ]
        );

        Role::factory()->create(
            [
                'name' => 'Role Beta',
            ]
        );
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    #[Test]
    public function guestShouldntListRoles()
    {
        $this->get('/roles')
            ->assertStatus(401);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    #[Test]
    public function administratorShouldListRoles()
    {
        $this->actingAs(self::$userAdm);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        $this->get('/roles')
            ->assertSee(['Role Alpha', 'Role Beta'])
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    #[Test]
    public function directorShouldListRoles()
    {
        $this->actingAs(self::$userDir);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        $this->get('/roles')
            ->assertSee(['Role Alpha', 'Role Beta'])
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    #[Test]
    public function assistantShouldListRoles()
    {
        $this->actingAs(self::$userAss);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        $this->get('/roles')
            ->assertSee(['Role Alpha', 'Role Beta'])
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    #[Test]
    public function secretaryShouldListRoles()
    {
        $this->actingAs(self::$userSec);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        $this->get('/roles')
            ->assertSee(['Role Alpha', 'Role Beta'])
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    #[Test]
    public function ldiShouldntListRoles()
    {
        $this->actingAs(self::$userLdi);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        $this->get('/roles')
            ->assertStatus(403);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    #[Test]
    public function coordinatorShouldListRoles()
    {
        $this->actingAs(self::$userCoord);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        $this->get('/roles')
            ->assertSee(['Role Alpha', 'Role Beta'])
            ->assertStatus(200);
    }
}
