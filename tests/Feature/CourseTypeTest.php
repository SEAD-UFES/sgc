<?php

namespace Tests\Feature;

use App\Models\CourseType;
use App\Models\User;
use App\Models\UserType;
use App\Models\UserTypeAssignment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CourseTypeTest extends TestCase
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

        CourseType::factory()->create(
            [
                'name' => 'Type Alpha',
                'description' => '1st type',
            ]
        );

        CourseType::factory()->create(
            [
                'name' => 'Type Beta',
                'description' => '2nd type',
            ]
        );
    }

    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function guestShouldntListCourseTypes()
    {
        $this->get('/coursetypes')
            ->assertRedirect(route('auth.login'));
    }

    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function administratorShouldListCourseTypes()
    {
        $this->actingAs(self::$userAdm)
            ->withSession(['loggedInUser.currentUta' => auth()->user()->getFirstUta(),]);

        $this->get('/coursetypes')
            ->assertSee(['Type Alpha', 'Type Beta', '1st type', '2nd type'])
            ->assertStatus(200);
    }


    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function directorShouldListCourseTypes()
    {
        $this->actingAs(self::$userDir)
            ->withSession(['loggedInUser.currentUta' => auth()->user()->getFirstUta(),]);

        $this->get('/coursetypes')
            ->assertSee(['Type Alpha', 'Type Beta', '1st type', '2nd type'])
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function assistantShouldListCourseTypes()
    {
        $this->actingAs(self::$userAss)
            ->withSession(['loggedInUser.currentUta' => auth()->user()->getFirstUta(),]);

        $this->get('/coursetypes')
            ->assertSee(['Type Alpha', 'Type Beta', '1st type', '2nd type'])
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function secretaryShouldListCourseTypes()
    {
        $this->actingAs(self::$userSec)
            ->withSession(['loggedInUser.currentUta' => auth()->user()->getFirstUta(),]);

        $this->get('/coursetypes')
            ->assertSee(['Type Alpha', 'Type Beta', '1st type', '2nd type'])
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function ldiShouldListCourseTypes()
    {
        $this->actingAs(self::$userLdi)
            ->withSession(['loggedInUser.currentUta' => auth()->user()->getFirstUta(),]);

        $this->get('/coursetypes')
            ->assertSee(['Type Alpha', 'Type Beta', '1st type', '2nd type'])
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function coordinatorShouldListCourseTypes()
    {
        $this->actingAs(self::$userCoord)
            ->withSession(['loggedInUser.currentUta' => auth()->user()->getFirstUta(),]);

        $this->get('/coursetypes')
            ->assertSee(['Type Alpha', 'Type Beta', '1st type', '2nd type'])
            ->assertStatus(200);
    }
}
