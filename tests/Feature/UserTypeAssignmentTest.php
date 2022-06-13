<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\UserType;
use App\Models\UserTypeAssignment;
use App\Models\Course;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTypeAssignmentTest extends TestCase
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

        UserTypeAssignment::factory()->create(
            [
                'user_id' => User::factory()->create(['email' => 'johndoe@test.com']),
                'user_type_id' => UserType::factory()->create(['name' => 'Type one']),
                'course_id' => Course::factory()->create(['name' => 'Course Alpha']),
                'begin' => now(),
                'end' => now(),
            ]
        );

        UserTypeAssignment::factory()->create(
            [
                'user_id' => User::factory()->create(['email' => 'janedoe@test2.com']),
                'user_type_id' => UserType::factory()->create(['name' => 'Type two']),
                'course_id' => Course::factory()->create(['name' => 'Course Beta']),
                'begin' => now(),
                'end' => now(),
            ]
        );
    }

    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function guestShouldntListUserTypeAssignments()
    {
        $this->get('/userTypeAssignments')
            ->assertRedirect(route('auth.login'));
    }

    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function administratorShouldListUserTypeAssignments()
    {
        $this->actingAs(self::$userAdm)
            ->withSession(['current_uta' => auth()->user()->getFirstUTA(), 'current_uta_id' => auth()->user()->getFirstUTA()->id]);

        $this->get('/userTypeAssignments')
            ->assertSee(['johndoe@test.com', 'janedoe@test2.com', 'Type one (Course Alpha)', 'Type two (Course Beta)'])
            ->assertStatus(200);
    }


    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function directorShouldntListUserTypeAssignments()
    {
        $this->actingAs(self::$userDir)
            ->withSession(['current_uta' => auth()->user()->getFirstUTA(), 'current_uta_id' => auth()->user()->getFirstUTA()->id]);

        $this->get('/userTypeAssignments')
            ->assertStatus(403);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function assistantShouldntListUserTypeAssignments()
    {
        $this->actingAs(self::$userAss)
            ->withSession(['current_uta' => auth()->user()->getFirstUTA(), 'current_uta_id' => auth()->user()->getFirstUTA()->id]);

        $this->get('/userTypeAssignments')
            ->assertStatus(403);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function secretaryShouldntListUserTypeAssignments()
    {
        $this->actingAs(self::$userSec)
            ->withSession(['current_uta' => auth()->user()->getFirstUTA(), 'current_uta_id' => auth()->user()->getFirstUTA()->id]);

        $this->get('/userTypeAssignments')
            ->assertStatus(403);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function ldiShouldntListUserTypeAssignments()
    {
        $this->actingAs(self::$userLdi)
            ->withSession(['current_uta' => auth()->user()->getFirstUTA(), 'current_uta_id' => auth()->user()->getFirstUTA()->id]);

        $this->get('/userTypeAssignments')
            ->assertStatus(403);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function coordinatorShouldntListUserTypeAssignments()
    {
        $this->actingAs(self::$userCoord)
            ->withSession(['current_uta' => auth()->user()->getFirstUTA(), 'current_uta_id' => auth()->user()->getFirstUTA()->id]);

        $this->get('/userTypeAssignments')
            ->assertStatus(403);
    }
}
