<?php

namespace Tests\Feature;

use App\Enums\KnowledgeAreas;
use App\Models\Bond;
use App\Models\BondCourse;
use App\Models\BondPole;
use App\Models\Course;
use App\Models\Employee;
use App\Models\Pole;
use App\Models\Role;
use App\Models\User;
use App\Models\UserType;
use App\Models\Responsibility;
use App\Repositories\ResponsibilityRepository;
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
    use WithFaker;

    private static User $userAdm;
    private static User $userDir;
    private static User $userAss;
    private static User $userSec;
    private static User $userCoord;
    private static User $userLdi;

    private ResponsibilityRepository $responsibilityRepository;

    public function __construct()
    {
        parent::__construct('BondTest');

        $this->responsibilityRepository = new ResponsibilityRepository();
    }

    public function setUp(): void
    {
        parent::setUp();

        User::factory()->withoutEmployee()->create([
            'login' => 'sgc_system',
        ]);

        self::$userAdm = User::factory()->create(
            [
                'login' => 'adm_email@test.com',
            ]
        );
        $userTypeAdm = UserType::factory()->admin()->create();
        Responsibility::factory()->create([
            'user_id' => self::$userAdm->id,
            'user_type_id' => $userTypeAdm->id,
            'course_id' => null,
        ]);

        self::$userDir = User::factory()->create(
            [
                'login' => 'dir_email@test.com',
            ]
        );
        $userTypeDir = UserType::factory()->director()->create();
        Responsibility::factory()->create([
            'user_id' => self::$userDir->id,
            'user_type_id' => $userTypeDir->id,
            'course_id' => null,
        ]);

        self::$userAss = User::factory()->create(
            [
                'login' => 'ass_email@test.com',
            ]
        );
        $userTypeAss = UserType::factory()->assistant()->create();
        Responsibility::factory()->create([
            'user_id' => self::$userAss->id,
            'user_type_id' => $userTypeAss->id,
            'course_id' => null,
        ]);

        self::$userSec = User::factory()->create(
            [
                'login' => 'sec_email@test.com',
            ]
        );
        $userTypeSec = UserType::factory()->secretary()->create();
        Responsibility::factory()->create([
            'user_id' => self::$userSec->id,
            'user_type_id' => $userTypeSec->id,
            'course_id' => null,
        ]);

        self::$userCoord = User::factory()->create(
            [
                'login' => 'coord_email@test.com',
            ]
        );
        $userTypeCoord = UserType::factory()->coordinator()->create();
        Responsibility::factory()->create([
            'user_id' => self::$userCoord->id,
            'user_type_id' => $userTypeCoord->id,
            'course_id' => Course::factory()->create()->id,
        ]);

        self::$userLdi = User::factory()->create(
            [
                'login' => 'ldi_email@test.com',
            ]
        );
        $userTypeLdi = UserType::factory()->ldi()->create();
        Responsibility::factory()->create([
            'user_id' => self::$userLdi->id,
            'user_type_id' => $userTypeLdi->id,
            'course_id' => null,
        ]);

        $bond = Bond::factory()->create(
            [
                'employee_id' => Employee::factory()->create(
                    [
                        'name' => 'John Doe',
                    ]
                ),
            ]
        );
        $course = Course::factory()->create(
            [
                'name' => 'Course Alpha',
            ]
        );
        (new BondCourse(
            [
                'bond_id' => $bond->getAttribute('id'),
                'course_id' => $course->getAttribute('id'),
            ]
        ))->save();


        $bond = Bond::factory()->create(
            [
                'employee_id' => Employee::factory()->create(
                    [
                        'name' => 'Jane Doe',
                    ]
                ),
            ]
        );
        $course = Course::factory()->create(
            [
                'name' => 'Course Beta',
            ]
        );
        (new BondCourse(
            [
                'bond_id' => $bond->getAttribute('id'),
                'course_id' => $course->getAttribute('id'),
            ]
        ))->save();
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
            ->assertStatus(401);
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
        $this->actingAs(self::$userAdm);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

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
        $this->actingAs(self::$userDir);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

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
        $this->actingAs(self::$userAss);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

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
        $this->actingAs(self::$userSec);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

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
        $this->actingAs(self::$userLdi);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

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
        $this->actingAs(self::$userCoord);

        /** @var User $authUser */
        $authUser = auth()->user();
        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

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
            ->assertStatus(401);
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
        $this->actingAs(self::$userAdm);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        $this->get('/bonds/create')
            ->assertSee(['Cadastrar Vínculo', 'Colaborador*', 'Função*', 'Curso', 'Polo', 'Voluntário', 'Cadastrar'])
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
        $this->actingAs(self::$userDir);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        $this->get('/bonds/create')
            ->assertSee(['Cadastrar Vínculo', 'Colaborador*', 'Função*', 'Curso', 'Polo', 'Voluntário', 'Cadastrar'])
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
        $this->actingAs(self::$userAss);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        $this->get('/bonds/create')
            ->assertSee(['Cadastrar Vínculo', 'Colaborador*', 'Função*', 'Curso', 'Polo', 'Voluntário', 'Cadastrar'])
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
        $this->actingAs(self::$userSec);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        $this->get('/bonds/create')
            ->assertSee(['Cadastrar Vínculo', 'Colaborador*', 'Função*', 'Curso', 'Polo', 'Voluntário', 'Cadastrar'])
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
        $this->actingAs(self::$userLdi);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

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
        $this->actingAs(self::$userCoord);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        $this->get('/bonds/create')
            ->assertSee(['Cadastrar Vínculo', 'Colaborador*', 'Função*', 'Curso', 'Polo', 'Voluntário', 'Cadastrar'])
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
        $bondArr = $this->createTestBondArray();
        Arr::forget($bondArr, ['id', 'created_at', 'updated_at']);
        $bondArr = array_merge($bondArr, $this->createQualificationDataArray());

        $this->post('/bonds', $bondArr)
            ->assertStatus(401);
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
        $this->actingAs(self::$userAdm);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        $bondArr = $this->createTestBondArray();
        Arr::forget($bondArr, ['id', 'created_at', 'updated_at']);
        $bondArr = array_merge($bondArr, $this->createQualificationDataArray());

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
        $this->actingAs(self::$userDir);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        $bondArr = $this->createTestBondArray();
        Arr::forget($bondArr, ['id', 'created_at', 'updated_at']);
        $bondArr = array_merge($bondArr, $this->createQualificationDataArray());

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
        $this->actingAs(self::$userAss);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        $bondArr = $this->createTestBondArray();
        Arr::forget($bondArr, ['id', 'created_at', 'updated_at']);
        $bondArr = array_merge($bondArr, $this->createQualificationDataArray());

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
        $this->actingAs(self::$userSec);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        $bondArr = $this->createTestBondArray();
        Arr::forget($bondArr, ['id', 'created_at', 'updated_at']);
        $bondArr = array_merge($bondArr, $this->createQualificationDataArray());

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
        $this->actingAs(self::$userLdi);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        $bondArr = $this->createTestBondArray();
        Arr::forget($bondArr, ['id', 'created_at', 'updated_at']);
        $bondArr = array_merge($bondArr, $this->createQualificationDataArray());

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
        $this->actingAs(self::$userCoord);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        /** @var Responsibility */
        $currentResponsibility = session('loggedInUser.currentResponsibility');
        /** @var Course */
        $coordinatorCourse = $currentResponsibility->course;

        $bondArr = $this->createTestBondArray(volunteer: false, courseId: intval($coordinatorCourse->getAttribute('id')));
        Arr::forget($bondArr, ['id', 'created_at', 'updated_at']);
        $bondArr = array_merge($bondArr, $this->createQualificationDataArray());

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
        $this->actingAs(self::$userCoord);

        /** @var User $authUser */
        $authUser = auth()->user();

        $this->withSession(['loggedInUser.currentResponsibility' => $this->responsibilityRepository->getFirstActiveResponsibilityByUserId(intval($authUser->getAttribute('id')))]);

        $bondArr = $this->createTestBondArray();
        Arr::forget($bondArr, ['id', 'created_at', 'updated_at']);
        $bondArr = array_merge($bondArr, $this->createQualificationDataArray());

        $this->followingRedirects()->post('/bonds', $bondArr)
            ->assertSee('403')
            ->assertStatus(403);
    }


    // $this->createTestBondArrayAsArray(volunteer: true, courseId: 24)
    /**
     * @param bool $volunteer
     * @param int $courseId
     *
     * @return array<string, string>
     *
     * @throws InvalidFormatException
     * @throws InvalidCastException
     */
    private function createTestBondArray(bool $volunteer = false, int $courseId = null): array
    {
        $course = Course::find($courseId) ?? Course::factory()->create(
            [
                'name' => 'Course Gama',
            ]
        );

        $pole = Pole::factory()->create(
            [
                'name' => 'Alabama Pole',
            ]
        );

        return [
            'employee_id' => strval(Employee::factory()->create(
                [
                    'name' => 'Carl Doe',
                ]
            )->getAttribute('id')),
            'role_id' => strval(Role::factory()->create(
                [
                    'name' => 'Role A',
                ]
            )->getAttribute('id')),
            'volunteer' => strval($volunteer),
            'hiring_process' => '01/2022',
            'begin' => strval(Carbon::today()->format('Y-m-d H:i:s')),
            'terminated_at' => strval(Carbon::today()->addYear()->format('Y-m-d H:i:s')),
            'course_id' => strval($course->getAttribute('id')),
            'pole_id' => strval($pole->getAttribute('id')),
        ];
    }

    /**
     * @return array<string, string>
     */
    private function createQualificationDataArray(): array
    {
        /** @var KnowledgeAreas */
        $knowledgeArea = $this->faker->randomElement(KnowledgeAreas::cases());

        return [
            'qualification_knowledge_area' => $knowledgeArea->name,
            'qualification_course' => $this->faker->name,
            'qualification_institution' => $this->faker->company,
        ];
    }

    /**
     * @param int $courseId
     *
     * @return array<int, string>
     */
    private function expectedBondInfo(int $courseId = null): array
    {
        $courseName = Course::find($courseId)?->name ?? 'Course Gama';
        return ['Carl Doe', 'Role A', $courseName, 'Alabama Pole'];
    }
}
