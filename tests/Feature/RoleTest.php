<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use App\Models\UserType;
use App\Models\UserTypeAssignment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RoleTest extends TestCase
{
    use RefreshDatabase;

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
     * @test
     */
    public function unloggedUserShouldntSeeRoles()
    {
        $response = $this->get('/roles');
        $response->assertRedirect(route('auth.login'));
    }

    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function administratorShouldSeeRoles()
    {
        $this->actingAs(self::$userAdm)
            ->withSession(['current_uta' => auth()->user()->getFirstUTA(), 'current_uta_id' => auth()->user()->getFirstUTA()->id]);

        $response = $this->get('/roles');
        $response->assertSee(['Role Alpha', 'Role Beta']);
        $response->assertStatus(200);
    }


    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function directorShouldSeeRoles()
    {
        $this->actingAs(self::$userDir)
            ->withSession(['current_uta' => auth()->user()->getFirstUTA(), 'current_uta_id' => auth()->user()->getFirstUTA()->id]);

        $response = $this->get('/roles');
        $response->assertSee(['Role Alpha', 'Role Beta']);
        $response->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function assistantShouldSeeRoles()
    {
        $this->actingAs(self::$userAss)
            ->withSession(['current_uta' => auth()->user()->getFirstUTA(), 'current_uta_id' => auth()->user()->getFirstUTA()->id]);

        $response = $this->get('/roles');
        $response->assertSee(['Role Alpha', 'Role Beta']);
        $response->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function secretaryShouldSeeRoles()
    {
        $this->actingAs(self::$userSec)
            ->withSession(['current_uta' => auth()->user()->getFirstUTA(), 'current_uta_id' => auth()->user()->getFirstUTA()->id]);

        $response = $this->get('/roles');
        $response->assertSee(['Role Alpha', 'Role Beta']);
        $response->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function ldiShouldntSeeRoles()
    {
        $this->actingAs(self::$userLdi)
            ->withSession(['current_uta' => auth()->user()->getFirstUTA(), 'current_uta_id' => auth()->user()->getFirstUTA()->id]);

        $response = $this->get('/roles');
        $response->assertStatus(403);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function coordinatorShouldSeeRoles()
    {
        $this->actingAs(self::$userCoord)
            ->withSession(['current_uta' => auth()->user()->getFirstUTA(), 'current_uta_id' => auth()->user()->getFirstUTA()->id]);

        $response = $this->get('/roles');
        $response->assertSee(['Role Alpha', 'Role Beta']);
        $response->assertStatus(200);
    }
}
