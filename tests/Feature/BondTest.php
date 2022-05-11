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
use Illuminate\Foundation\Testing\WithFaker;
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
            'course_id' => Course::factory()->create()->id,
        ]);

        self::$userLdi = User::factory()->create();
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
     * @test
     */
    public function unloggedUserShouldntSeeBonds()
    {
        $response = $this->get('/bonds');
        $response->assertRedirect(route('auth.login'));
    }

    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function administratorShouldSeeBonds()
    {
        $this->actingAs(self::$userAdm)
            ->withSession(['current_uta' => auth()->user()->getFirstUTA(), 'current_uta_id' => auth()->user()->getFirstUTA()->id]);

        $response = $this->get('/bonds');
        $response->assertSee(['John Doe', 'Jane Doe', 'Course Alpha', 'Course Beta']);
        $response->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function directorShouldSeeBonds()
    {
        $this->actingAs(self::$userDir)
            ->withSession(['current_uta' => auth()->user()->getFirstUTA(), 'current_uta_id' => auth()->user()->getFirstUTA()->id]);

        $response = $this->get('/bonds');
        $response->assertSee(['John Doe', 'Jane Doe', 'Course Alpha', 'Course Beta']);
        $response->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function assistantShouldSeeBonds()
    {
        $this->actingAs(self::$userAss)
            ->withSession(['current_uta' => auth()->user()->getFirstUTA(), 'current_uta_id' => auth()->user()->getFirstUTA()->id]);

        $response = $this->get('/bonds');
        $response->assertSee(['John Doe', 'Jane Doe', 'Course Alpha', 'Course Beta']);
        $response->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function secretaryShouldSeeBonds()
    {
        $this->actingAs(self::$userSec)
            ->withSession(['current_uta' => auth()->user()->getFirstUTA(), 'current_uta_id' => auth()->user()->getFirstUTA()->id]);

        $response = $this->get('/bonds');
        $response->assertSee(['John Doe', 'Jane Doe', 'Course Alpha', 'Course Beta']);
        $response->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function ldiShouldntSeeBonds()
    {
        $this->actingAs(self::$userLdi)
            ->withSession(['current_uta' => auth()->user()->getFirstUTA(), 'current_uta_id' => auth()->user()->getFirstUTA()->id]);

        $response = $this->get('/bonds');
        $response->assertStatus(403);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function coordinatorShouldSeeBonds()
    {
        $this->actingAs(self::$userCoord)
            ->withSession(['current_uta' => auth()->user()->getFirstUTA(), 'current_uta_id' => auth()->user()->getFirstUTA()->id]);

        $response = $this->get('/bonds');
        $response->assertSee(['John Doe', 'Jane Doe', 'Course Alpha', 'Course Beta']);
        $response->assertStatus(200);
    }


    // ================= See Create Form Tests =================

    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function unloggedUserShouldntSeeCreateBondsForm()
    {
        $response = $this->get('/bonds/create');
        $response->assertRedirect(route('auth.login'));
    }

    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function administratorShouldSeeCreateBondsForm()
    {
        $this->actingAs(self::$userAdm)
            ->withSession(['current_uta' => auth()->user()->getFirstUTA(), 'current_uta_id' => auth()->user()->getFirstUTA()->id]);

        $response = $this->get('/bonds/create');
        $response->assertSee(['Cadastrar Vínculo', 'Colaborador*', 'Função*', 'Curso*', 'Polo*', 'Voluntário', 'Cadastrar']);
        $response->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function directorShouldSeeCreateBondsForm()
    {
        $this->actingAs(self::$userDir)
            ->withSession(['current_uta' => auth()->user()->getFirstUTA(), 'current_uta_id' => auth()->user()->getFirstUTA()->id]);

        $response = $this->get('/bonds/create');
        $response->assertSee(['Cadastrar Vínculo', 'Colaborador*', 'Função*', 'Curso*', 'Polo*', 'Voluntário', 'Cadastrar']);
        $response->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function assistantShouldSeeCreateBondsForm()
    {
        $this->actingAs(self::$userAss)
            ->withSession(['current_uta' => auth()->user()->getFirstUTA(), 'current_uta_id' => auth()->user()->getFirstUTA()->id]);

        $response = $this->get('/bonds/create');
        $response->assertSee(['Cadastrar Vínculo', 'Colaborador*', 'Função*', 'Curso*', 'Polo*', 'Voluntário', 'Cadastrar']);
        $response->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function secretaryShouldSeeCreateBondsForm()
    {
        $this->actingAs(self::$userSec)
            ->withSession(['current_uta' => auth()->user()->getFirstUTA(), 'current_uta_id' => auth()->user()->getFirstUTA()->id]);

        $response = $this->get('/bonds/create');
        $response->assertSee(['Cadastrar Vínculo', 'Colaborador*', 'Função*', 'Curso*', 'Polo*', 'Voluntário', 'Cadastrar']);
        $response->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function ldiShouldntSeeCreateBondsForm()
    {
        $this->actingAs(self::$userLdi)
            ->withSession(['current_uta' => auth()->user()->getFirstUTA(), 'current_uta_id' => auth()->user()->getFirstUTA()->id]);

        $response = $this->get('/bonds/create');
        $response->assertStatus(403);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function coordinatorShouldSeeCreateBondsForm()
    {
        $this->actingAs(self::$userCoord)
            ->withSession(['current_uta' => auth()->user()->getFirstUTA(), 'current_uta_id' => auth()->user()->getFirstUTA()->id]);

        $response = $this->get('/bonds/create');
        $response->assertSee(['Cadastrar Vínculo', 'Colaborador*', 'Função*', 'Curso*', 'Polo*', 'Voluntário', 'Cadastrar']);
        $response->assertStatus(200);
    }


    // ================= Create Bond Tests =================

    // $this->createTestBondAsArray(volunteer: true, courseId: 24)
    /**
     * @param null|bool $volunteer
     * @param null|int $courseId
     * @return array
     * @throws InvalidFormatException
     * @throws InvalidCastException
     */
    private function createTestBondAsArray(?bool $volunteer = false, ?int $courseId = null): array
    {
        $bondArr = Bond::factory()->make(
            [
                'course_id' => $courseId ?? Course::factory()->create(
                    [
                        'name' => 'Course Gama',
                    ]
                ),
                'employee_id' => Employee::factory()->create(
                    [
                        'name' => 'Carl Doe',
                    ]
                ),
                'role_id' => Role::factory()->create(
                    [
                        'name' => 'Role A',
                    ]
                ),
                'pole_id' => Pole::factory()->create(
                    [
                        'name' => 'Alabama Pole',
                    ]
                ),
                'begin' => Carbon::today()->format('Y-m-d H:i:s'),
                'end' => Carbon::today()->addYear()->format('Y-m-d H:i:s'),
                'volunteer' => $volunteer,
            ]
        )->toArray();

        Arr::forget($bondArr, ['id', 'impediment', 'impediment_description', 'uaba_checked_at', 'created_at', 'updated_at']);

        return $bondArr;
    }

    /**
     * @param null|int $courseId
     * @return array
     */
    private function expectedBondInfo(?int $courseId = null): array
    {
        return ['Carl Doe', 'Role A', $courseId ? Course::find($courseId)->name : 'Course Gama', 'Alabama Pole'];
    }

    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function unloggedUserShouldntCreateBond()
    {
        $bond = $this->createTestBondAsArray();

        $response = $this->post('/bonds', $bond);
        $response->assertRedirect(route('auth.login'));
    }

    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function administratorShouldCreateBond()
    {
        $this->actingAs(self::$userAdm)
            ->withSession(['current_uta' => auth()->user()->getFirstUTA(), 'current_uta_id' => auth()->user()->getFirstUTA()->id]);

        $bond = $this->createTestBondAsArray();
        $response = $this->followingRedirects()->post('/bonds', $bond);

        $response->assertSee($this->expectedBondInfo());
        $response->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function directorShouldCreateBond()
    {
        $this->actingAs(self::$userDir)
            ->withSession(['current_uta' => auth()->user()->getFirstUTA(), 'current_uta_id' => auth()->user()->getFirstUTA()->id]);

        $bond = $this->createTestBondAsArray();
        $response = $this->followingRedirects()->post('/bonds', $bond);

        $response->assertSee($this->expectedBondInfo());
        $response->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function assistantShouldCreateBond()
    {
        $this->actingAs(self::$userAss)
            ->withSession(['current_uta' => auth()->user()->getFirstUTA(), 'current_uta_id' => auth()->user()->getFirstUTA()->id]);

        $bond = $this->createTestBondAsArray();
        $response = $this->followingRedirects()->post('/bonds', $bond);

        $response->assertSee($this->expectedBondInfo());
        $response->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function secretaryShouldCreateBond()
    {
        $this->actingAs(self::$userSec)
            ->withSession(['current_uta' => auth()->user()->getFirstUTA(), 'current_uta_id' => auth()->user()->getFirstUTA()->id]);

        $bond = $this->createTestBondAsArray();

        $response = $response = $this->followingRedirects()->post('/bonds', $bond);

        $response->assertSee($this->expectedBondInfo());
        $response->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function ldiShouldntCreateBond()
    {
        $this->actingAs(self::$userLdi)
            ->withSession(['current_uta' => auth()->user()->getFirstUTA(), 'current_uta_id' => auth()->user()->getFirstUTA()->id]);

        $bond = $this->createTestBondAsArray();

        $response = $response = $this->followingRedirects()->post('/bonds', $bond);
        $response->assertStatus(403);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function coordinatorOfSameCourseShouldCreateBond()
    {
        $this->actingAs(self::$userCoord)
            ->withSession(['current_uta' => auth()->user()->getFirstUTA(), 'current_uta_id' => auth()->user()->getFirstUTA()->id]);

        $coordinatorCourse = auth()->user()->getCurrentUTA()->course;

        $bond = $this->createTestBondAsArray(courseId: $coordinatorCourse->id);
        $response = $this->followingRedirects()->post('/bonds', $bond);

        $response->assertSee($this->expectedBondInfo($coordinatorCourse->id));
        $response->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function coordinatorOfAnotherCourseShouldntCreateBond()
    {
        $this->actingAs(self::$userCoord)
            ->withSession(['current_uta' => auth()->user()->getFirstUTA(), 'current_uta_id' => auth()->user()->getFirstUTA()->id]);

        $bond = $this->createTestBondAsArray();
        $response = $this->followingRedirects()->post('/bonds', $bond);

        $response->assertSee('O usuário não pode escolher esse curso');
        $response->assertStatus(200);
    }
}
