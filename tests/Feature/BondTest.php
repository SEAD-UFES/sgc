<?php

namespace Tests\Feature;

use App\Models\Bond;
use App\Models\Course;
use App\Models\Employee;
use App\Models\Pole;
use App\Models\Role;
use App\Models\User;
use App\Models\UserType;
use App\Models\UserTypeAssignment;
use Carbon\Carbon;
use Carbon\Exceptions\InvalidFormatException;
use Illuminate\Database\Eloquent\InvalidCastException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Tests\TestCase;

class BondTest extends TestCase
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

        self::$userAdm = User::factory()->create(
            [
                'email' => 'adm_email@test.com',
            ]
        );
        $userTypeAdm = UserType::factory()->admin()->create();
        UserTypeAssignment::factory()->create([
            'user_id' => self::$userAdm->id,
            'user_type_id' => $userTypeAdm->id,
            'course_id' => null,
        ]);

        self::$userDir = User::factory()->create(
            [
                'email' => 'dir_email@test.com',
            ]
        );
        $userTypeDir = UserType::factory()->director()->create();
        UserTypeAssignment::factory()->create([
            'user_id' => self::$userDir->id,
            'user_type_id' => $userTypeDir->id,
            'course_id' => null,
        ]);

        self::$userAss = User::factory()->create(
            [
                'email' => 'ass_email@test.com',
            ]
        );
        $userTypeAss = UserType::factory()->assistant()->create();
        UserTypeAssignment::factory()->create([
            'user_id' => self::$userAss->id,
            'user_type_id' => $userTypeAss->id,
            'course_id' => null,
        ]);

        self::$userSec = User::factory()->create(
            [
                'email' => 'sec_email@test.com',
            ]
        );
        $userTypeSec = UserType::factory()->secretary()->create();
        UserTypeAssignment::factory()->create([
            'user_id' => self::$userSec->id,
            'user_type_id' => $userTypeSec->id,
            'course_id' => null,
        ]);

        self::$userCoord = User::factory()->create(
            [
                'email' => 'coord_email@test.com',
            ]
        );
        $userTypeCoord = UserType::factory()->coordinator()->create();
        UserTypeAssignment::factory()->create([
            'user_id' => self::$userCoord->id,
            'user_type_id' => $userTypeCoord->id,
            'course_id' => Course::factory()->create()->id,
        ]);

        self::$userLdi = User::factory()->create(
            [
                'email' => 'ldi_email@test.com',
            ]
        );
        $userTypeLdi = UserType::factory()->ldi()->create();
        UserTypeAssignment::factory()->create([
            'user_id' => self::$userLdi->id,
            'user_type_id' => $userTypeLdi->id,
            'course_id' => null,
        ]);

        Bond::factory()->create(
            [
                'course_id' => Course::factory()->create(
                    [
                        'name' => 'Course Alpha',
                    ]
                ),
                'employee_id' => Employee::factory()->create(
                    [
                        'name' => 'John Doe',
                    ]
                ),
            ]
        );

        Bond::factory()->create(
            [
                'course_id' => Course::factory()->create(
                    [
                        'name' => 'Course Beta',
                    ]
                ),
                'employee_id' => Employee::factory()->create(
                    [
                        'name' => 'Jane Doe',
                    ]
                ),
            ]
        );
    }

    /**
     * A basic feature test example.
     *
     * @return void
     *
     * @test
     */
    public function guestShouldntListBonds()
    {
        $this->get('/bonds')
            ->assertRedirect(route('auth.login'));
    }

    /**
     * A basic feature test example.
     *
     * @return void
     *
     * @test
     */
    public function administratorShouldListBonds()
    {
        $this->actingAs(self::$userAdm)
            ->withSession(['loggedInUser.currentUta' => auth()->user()->getFirstUta()]);

        $this->get('/bonds')
            ->assertSee(['John Doe', 'Jane Doe', 'Course Alpha', 'Course Beta'])
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     *
     * @test
     */
    public function directorShouldListBonds()
    {
        $this->actingAs(self::$userDir)
            ->withSession(['loggedInUser.currentUta' => auth()->user()->getFirstUta()]);

        $this->get('/bonds')
            ->assertSee(['John Doe', 'Jane Doe', 'Course Alpha', 'Course Beta'])
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     *
     * @test
     */
    public function assistantShouldListBonds()
    {
        $this->actingAs(self::$userAss)
            ->withSession(['loggedInUser.currentUta' => auth()->user()->getFirstUta()]);

        $this->get('/bonds')
            ->assertSee(['John Doe', 'Jane Doe', 'Course Alpha', 'Course Beta'])
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     *
     * @test
     */
    public function secretaryShouldListBonds()
    {
        $this->actingAs(self::$userSec)
            ->withSession(['loggedInUser.currentUta' => auth()->user()->getFirstUta()]);

        $this->get('/bonds')
            ->assertSee(['John Doe', 'Jane Doe', 'Course Alpha', 'Course Beta'])
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     *
     * @test
     */
    public function ldiShouldntListBonds()
    {
        $this->actingAs(self::$userLdi)
            ->withSession(['loggedInUser.currentUta' => auth()->user()->getFirstUta()]);

        $this->get('/bonds')
            ->assertStatus(403);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     *
     * @test
     */
    public function coordinatorShouldListBonds()
    {
        $this->actingAs(self::$userCoord)
            ->withSession(['loggedInUser.currentUta' => auth()->user()->getFirstUta()]);

        $this->get('/bonds')
            ->assertSee(['John Doe', 'Jane Doe', 'Course Alpha', 'Course Beta'])
            ->assertStatus(200);
    }

    
    // ================= See Create Form Tests =================

    /**
     * A basic feature test example.
     *
     * @return void
     *
     * @test
     */
    public function guestShouldntAccessCreateBondsPage()
    {
        $this->get('/bonds/create')
            ->assertRedirect(route('auth.login'));
    }

    /**
     * A basic feature test example.
     *
     * @return void
     *
     * @test
     */
    public function administratorShouldAccessCreateBondsPage()
    {
        $this->actingAs(self::$userAdm)
            ->withSession(['loggedInUser.currentUta' => auth()->user()->getFirstUta()]);

        $this->get('/bonds/create')
            ->assertSee(['Cadastrar Vínculo', 'Colaborador*', 'Função*', 'Curso*', 'Polo*', 'Voluntário', 'Cadastrar'])
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     *
     * @test
     */
    public function directorShouldAccessCreateBondsPage()
    {
        $this->actingAs(self::$userDir)
            ->withSession(['loggedInUser.currentUta' => auth()->user()->getFirstUta()]);

        $this->get('/bonds/create')
            ->assertSee(['Cadastrar Vínculo', 'Colaborador*', 'Função*', 'Curso*', 'Polo*', 'Voluntário', 'Cadastrar'])
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     *
     * @test
     */
    public function assistantShouldAccessCreateBondsPage()
    {
        $this->actingAs(self::$userAss)
            ->withSession(['loggedInUser.currentUta' => auth()->user()->getFirstUta()]);

        $this->get('/bonds/create')
            ->assertSee(['Cadastrar Vínculo', 'Colaborador*', 'Função*', 'Curso*', 'Polo*', 'Voluntário', 'Cadastrar'])
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     *
     * @test
     */
    public function secretaryShouldAccessCreateBondsPage()
    {
        $this->actingAs(self::$userSec)
            ->withSession(['loggedInUser.currentUta' => auth()->user()->getFirstUta()]);

        $this->get('/bonds/create')
            ->assertSee(['Cadastrar Vínculo', 'Colaborador*', 'Função*', 'Curso*', 'Polo*', 'Voluntário', 'Cadastrar'])
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     *
     * @test
     */
    public function ldiShouldntAccessCreateBondsPage()
    {
        $this->actingAs(self::$userLdi)
            ->withSession(['loggedInUser.currentUta' => auth()->user()->getFirstUta()]);

        $this->get('/bonds/create')
            ->assertStatus(403);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     *
     * @test
     */
    public function coordinatorShouldAccessCreateBondsPage()
    {
        $this->actingAs(self::$userCoord)
        ->withSession(['loggedInUser.currentUta' => auth()->user()->getFirstUta()]);
        
        $this->get('/bonds/create')
        ->assertSee(['Cadastrar Vínculo', 'Colaborador*', 'Função*', 'Curso*', 'Polo*', 'Voluntário', 'Cadastrar'])
        ->assertStatus(200);
    }
    
    
    // ================= Create Bond Tests =================

    /**
     * A basic feature test example.
     *
     * @return void
     *
     * @test
     */
    public function guestShouldntCreateBond()
    {
        $bondArr = $this->createTestBond()->toArray();
        Arr::forget($bondArr, ['id', 'impediment', 'impediment_description', 'uaba_checked_at', 'created_at', 'updated_at']);

        $this->post('/bonds', $bondArr)
            ->assertRedirect(route('auth.login'));
    }

    /**
     * A basic feature test example.
     *
     * @return void
     *
     * @test
     */
    public function administratorShouldCreateBond()
    {
        $this->actingAs(self::$userAdm)
            ->withSession(['loggedInUser.currentUta' => auth()->user()->getFirstUta()]);

        $bondArr = $this->createTestBond()->toArray();
        Arr::forget($bondArr, ['id', 'impediment', 'impediment_description', 'uaba_checked_at', 'created_at', 'updated_at']);

        $this->followingRedirects()->post('/bonds', $bondArr)
            ->assertSee($this->expectedBondInfo())
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     *
     * @test
     */
    public function directorShouldCreateBond()
    {
        $this->actingAs(self::$userDir)
            ->withSession(['loggedInUser.currentUta' => auth()->user()->getFirstUta()]);

        $bondArr = $this->createTestBond()->toArray();
        Arr::forget($bondArr, ['id', 'impediment', 'impediment_description', 'uaba_checked_at', 'created_at', 'updated_at']);

        $this->followingRedirects()->post('/bonds', $bondArr)
            ->assertSee($this->expectedBondInfo())
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     *
     * @test
     */
    public function assistantShouldCreateBond()
    {
        $this->actingAs(self::$userAss)
            ->withSession(['loggedInUser.currentUta' => auth()->user()->getFirstUta()]);

        $bondArr = $this->createTestBond()->toArray();
        Arr::forget($bondArr, ['id', 'impediment', 'impediment_description', 'uaba_checked_at', 'created_at', 'updated_at']);

        $this->followingRedirects()->post('/bonds', $bondArr)
            ->assertSee($this->expectedBondInfo())
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     *
     * @test
     */
    public function secretaryShouldCreateBond()
    {
        $this->actingAs(self::$userSec)
            ->withSession(['loggedInUser.currentUta' => auth()->user()->getFirstUta()]);

        $bondArr = $this->createTestBond()->toArray();
        Arr::forget($bondArr, ['id', 'impediment', 'impediment_description', 'uaba_checked_at', 'created_at', 'updated_at']);

        $this->followingRedirects()->post('/bonds', $bondArr)
            ->assertSee($this->expectedBondInfo())
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     *
     * @test
     */
    public function ldiShouldntCreateBond()
    {
        $this->actingAs(self::$userLdi)
            ->withSession(['loggedInUser.currentUta' => auth()->user()->getFirstUta()]);

        $bondArr = $this->createTestBond()->toArray();
        Arr::forget($bondArr, ['id', 'impediment', 'impediment_description', 'uaba_checked_at', 'created_at', 'updated_at']);

        $this->followingRedirects()->post('/bonds', $bondArr)
            ->assertStatus(403);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     *
     * @test
     */
    public function coordinatorOfSameCourseShouldCreateBond()
    {
        $this->actingAs(self::$userCoord)
            ->withSession(['loggedInUser.currentUta' => auth()->user()->getFirstUta()]);

        $coordinatorCourse = auth()->user()->getCurrentUta()->course;

        $bondArr = $this->createTestBond(courseId: $coordinatorCourse->id)->toArray();
        Arr::forget($bondArr, ['id', 'impediment', 'impediment_description', 'uaba_checked_at', 'created_at', 'updated_at']);

        $this->followingRedirects()->post('/bonds', $bondArr)
            ->assertSee($this->expectedBondInfo($coordinatorCourse->id))
            ->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     *
     * @test
     */
    public function coordinatorOfAnotherCourseShouldntCreateBond()
    {
        $this->actingAs(self::$userCoord)
            ->withSession(['loggedInUser.currentUta' => auth()->user()->getFirstUta()]);

        $bondArr = $this->createTestBond()->toArray();
        Arr::forget($bondArr, ['id', 'impediment', 'impediment_description', 'uaba_checked_at', 'created_at', 'updated_at']);

        $this->followingRedirects()->post('/bonds', $bondArr)
            ->assertSee('O usuário não pode escolher esse curso')
            ->assertStatus(200);
    }


    // $this->createTestBondAsArray(volunteer: true, courseId: 24)
    /**
     * @param bool|null $volunteer
     * @param int|null $courseId
     *
     * @return Bond
     *
     * @throws InvalidFormatException
     * @throws InvalidCastException
     */
    private function createTestBond(?bool $volunteer = false, ?int $courseId = null): Bond
    {
        return Bond::factory()->create(
            [
                'course_id' => $courseId ?? Course::factory()->create(
                    [
                        'name' => 'Course Gama',
                    ]
                )->id,
                'employee_id' => Employee::factory()->create(
                    [
                        'name' => 'Carl Doe',
                    ]
                )->id,
                'role_id' => Role::factory()->create(
                    [
                        'name' => 'Role A',
                    ]
                )->id,
                'pole_id' => Pole::factory()->create(
                    [
                        'name' => 'Alabama Pole',
                    ]
                )->id,
                'begin' => Carbon::today()->format('Y-m-d H:i:s'),
                'end' => Carbon::today()->addYear()->format('Y-m-d H:i:s'),
                'volunteer' => $volunteer,
            ]
        );
    }

    /**
     * @param int|null $courseId
     *
     * @return array
     */
    private function expectedBondInfo(?int $courseId = null): array
    {
        return ['Carl Doe', 'Role A', $courseId ? Course::find($courseId)->name : 'Course Gama', 'Alabama Pole'];
    }
}
