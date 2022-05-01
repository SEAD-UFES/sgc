<?php

namespace Tests\Feature;

use App\Models\BondDocument;
use App\Models\Document;
use App\Models\EmployeeDocument;
use App\Models\User;
use App\Models\UserType;
use App\Models\UserTypeAssignment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class DocumentTest extends TestCase
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



        Document::factory()->create(
            [
                'original_name' => 'Document Employee Alpha.pdf',
                'documentable_id' => EmployeeDocument::factory()->create()->id,
                'documentable_type' => EmployeeDocument::class,
            ]
        );

        Document::factory()->create(
            [
                'original_name' => 'Document Bond Beta.pdf',
                'documentable_id' => BondDocument::factory()->create()->id,
                'documentable_type' => BondDocument::class,
            ]
        );
    }

    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function unloggedUserShouldntSeeEmployeesDocuments()
    {
        $response = $this->get('/employeesdocuments');
        $response->assertRedirect(route('auth.login'));
    }

    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function administratorShouldSeeEmployeesDocuments()
    {
        $this->be(self::$userAdm)
            ->withSession(['current_uta' => auth()->user()->getFirstUTA(), 'current_uta_id' => auth()->user()->getFirstUTA()->id]);

        $response = $this->get('/employeesdocuments');
        $response->assertSee('Document Employee Alpha.pdf');
        $response->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function directorShouldSeeEmployeesDocuments()
    {
        $this->be(self::$userDir)
            ->withSession(['current_uta' => auth()->user()->getFirstUTA(), 'current_uta_id' => auth()->user()->getFirstUTA()->id]);

        $response = $this->get('/employeesdocuments');
        $response->assertSee('Document Employee Alpha.pdf');
        $response->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function assistantShouldSeeEmployeesDocuments()
    {
        $this->be(self::$userAss)
            ->withSession(['current_uta' => auth()->user()->getFirstUTA(), 'current_uta_id' => auth()->user()->getFirstUTA()->id]);

        $response = $this->get('/employeesdocuments');
        $response->assertSee('Document Employee Alpha.pdf');
        $response->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function secretaryShouldSeeEmployeesDocuments()
    {
        $this->be(self::$userSec)
            ->withSession(['current_uta' => auth()->user()->getFirstUTA(), 'current_uta_id' => auth()->user()->getFirstUTA()->id]);

        $response = $this->get('/employeesdocuments');
        $response->assertSee('Document Employee Alpha.pdf');
        $response->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function ldiShouldntSeeEmployeesDocuments()
    {
        $this->be(self::$userLdi)
            ->withSession(['current_uta' => auth()->user()->getFirstUTA(), 'current_uta_id' => auth()->user()->getFirstUTA()->id]);

        $response = $this->get('/employeesdocuments');
        $response->assertStatus(403);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function coordinatorShouldntSeeEmployeesDocuments()
    {
        $this->be(self::$userCoord)
            ->withSession(['current_uta' => auth()->user()->getFirstUTA(), 'current_uta_id' => auth()->user()->getFirstUTA()->id]);

        $response = $this->get('/employeesdocuments');
        $response->assertStatus(403);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function unloggedUserShouldntSeeBondsDocuments()
    {
        $response = $this->get('/bondsdocuments');
        $response->assertRedirect(route('auth.login'));
    }

    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function administratorShouldSeeBondsDocuments()
    {
        $this->be(self::$userAdm)
            ->withSession(['current_uta' => auth()->user()->getFirstUTA(), 'current_uta_id' => auth()->user()->getFirstUTA()->id]);

        $response = $this->get('/bondsdocuments');
        $response->assertSee('Document Bond Beta.pdf');
        $response->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function directorShouldSeeBondsDocuments()
    {
        $this->be(self::$userDir)
            ->withSession(['current_uta' => auth()->user()->getFirstUTA(), 'current_uta_id' => auth()->user()->getFirstUTA()->id]);

        $response = $this->get('/bondsdocuments');
        $response->assertSee('Document Bond Beta.pdf');
        $response->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function assistantShouldSeeBondsDocuments()
    {
        $this->be(self::$userAss)
            ->withSession(['current_uta' => auth()->user()->getFirstUTA(), 'current_uta_id' => auth()->user()->getFirstUTA()->id]);

        $response = $this->get('/bondsdocuments');
        $response->assertSee('Document Bond Beta.pdf');
        $response->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function secretaryShouldSeeBondsDocuments()
    {
        $this->be(self::$userSec)
            ->withSession(['current_uta' => auth()->user()->getFirstUTA(), 'current_uta_id' => auth()->user()->getFirstUTA()->id]);

        $response = $this->get('/bondsdocuments');
        $response->assertSee('Document Bond Beta.pdf');
        $response->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function ldiShouldntSeeBondsDocuments()
    {
        $this->be(self::$userLdi)
            ->withSession(['current_uta' => auth()->user()->getFirstUTA(), 'current_uta_id' => auth()->user()->getFirstUTA()->id]);

        $response = $this->get('/bondsdocuments');
        $response->assertStatus(403);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function coordinatorShouldntSeeBondsDocuments()
    {
        $this->be(self::$userCoord)
            ->withSession(['current_uta' => auth()->user()->getFirstUTA(), 'current_uta_id' => auth()->user()->getFirstUTA()->id]);

        $response = $this->get('/bondsdocuments');
        $response->assertStatus(403);
    }
}
