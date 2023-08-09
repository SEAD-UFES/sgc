<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use App\Events\ModelListed;
use App\Events\ModelRead;
use App\Models\Applicant;
use App\Models\Course;
use App\Models\Pole;
use App\Models\Role;
use App\Services\ApplicantService;
use App\Services\Dto\ApplicantDto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class ApplicantServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var ApplicantService $service
     */
    private $service;

    //setting up scenario for all tests
    protected function setUp(): void
    {
        parent::setUp();

        Applicant::factory()->create(
            [
                'name' => 'John Doe',
                'email' => 'john@test.com',
                'area_code' => '01',
                'landline' => '12345678',
                'mobile' => '123456789',
                'hiring_process' => '001',
            ]
        );

        Applicant::factory()->create(
            [
                'name' => 'Jane Doe',
                'email' => 'jane@othertest.com',
                'area_code' => '02',
                'landline' => '01234567',
                'mobile' => '012345678',
                'hiring_process' => '002',
            ]
        );

        $this->service = new ApplicantService();
    }

    /**
     *
     * @return void
     */
    #[Test]
    public function applicantsShouldBeListed()
    {
        Event::fakeFor(function () {
            //execution
            $applicants = $this->service->list();

            //verifications
            Event::assertDispatched(ModelListed::class);
            $this->assertContains('John Doe', $applicants->pluck('name')->toArray());
            $this->assertContains('Jane Doe', $applicants->pluck('name')->toArray());
            $this->assertCount(2, $applicants);
        });
    }

    /**
     *
     * @return void
     */
    #[Test]
    public function applicantShouldBeRetrieved()
    {
        //setting up scenario
        /**
         * @var Applicant $applicant
         */
        $applicant = Applicant::find(1);

        Event::fakeFor(function () use ($applicant) {
            //execution
            $applicant = $this->service->read($applicant);

            //verifications
            Event::assertDispatched(ModelRead::class);
            $this->assertEquals('John Doe', $applicant->name);
            $this->assertCount(2, Applicant::all());
        });
    }

    /**
     *
     * @return void
     */
    #[Test]
    public function applicantShouldBeDeleted()
    {
        //setting up scenario
        /**
         * @var Applicant $applicant
         */
        $applicant = Applicant::find(1);

        Event::fakeFor(function () use ($applicant) {
            //execution
            $this->service->delete($applicant);

            //verifications
            Event::assertDispatched('eloquent.deleted: ' . Applicant::class);
            $this->assertEquals('Jane Doe', $this->service->list()->first()?->name);
            $this->assertCount(1, Applicant::all());
        });
    }

    /**
     *
     * @return void
     */
    #[Test]
    public function applicantStateShouldChange()
    {
        /**
         * @var array<string, string> $attributes
         */
        $attributes = [];
        $attributes['states'] = 'NC';

        /**
         * @var Applicant $applicant
         */
        $applicant = Applicant::find(1);

        Event::fakeFor(function () use ($applicant, $attributes) {
            //execution
            $this->service->changeState($attributes, $applicant);

            //verifications
            Event::assertDispatched('eloquent.saved: ' . Applicant::class);
            $this->assertEquals('NC', $applicant->call_state->name);
            $this->assertCount(2, Applicant::all());
        });
    }

    /**
     *
     * @return void
     */
    #[Test]
    public function applicantShouldBeCreated()
    {
        /**
         * @var array<string, string> $attributes
         */
        $attributes = [];

        $attributes['name'] = 'Dilan Doe';
        $attributes['email'] = 'dilan@othertest.com';
        $attributes['areaCode'] = '03';
        $attributes['landline'] = '01234567';
        $attributes['mobile'] = '012345678';
        $attributes['hiringProcess'] = '003';

        $attributes['roleId'] = strval(Role::factory()->createOne(
            [
                'name' => 'Super Role',
                'description' => 'Super Role',
            ]
        )->getAttribute('id'));

        $attributes['courseId'] = strval(Course::factory()->createOne(
            [
                'name' => 'Course Omicron',
                'description' => 'Course Omicron',
            ]
        )->getAttribute('id'));

        $attributes['poleId'] = strval(Pole::factory()->createOne(
            [
                'name' => 'Pole Teta',
                'description' => 'Pole Teta',
            ]
        )->getAttribute('id'));

        $dto = new ApplicantDto(...$attributes);

        Event::fakeFor(function () use ($dto) {
            //execution
            $this->service->create($dto);

            //verifications
            Event::assertDispatched('eloquent.created: ' . Applicant::class);
            $this->assertEquals('Dilan Doe', Applicant::find(3)?->name);
            $this->assertCount(3, Applicant::all());
        });
    }

    /**
     *
     * @return void
     */
    #[Test]
    public function shouldPersistApplicantsList()
    {
        $applicants = [];

        $applicants[0]['check'] = true;
        $applicants[0]['name'] = 'Bob Doe';
        $applicants[0]['email'] = 'bob@test3.com';
        $applicants[0]['area_code'] = '27';
        $applicants[0]['landline'] = '33333333';
        $applicants[0]['mobile'] = '99999999';
        $applicants[0]['hiring_process'] = '003';
        $applicants[0]['role_id'] = '1';
        $applicants[0]['course_id'] = '1';
        $applicants[0]['pole_id'] = '1';

        $applicants[1]['check'] = true;
        $applicants[1]['name'] = 'Mary Doe';
        $applicants[1]['email'] = 'mary@test4.com';
        $applicants[1]['area_code'] = '27';
        $applicants[1]['landline'] = '33333333';
        $applicants[1]['mobile'] = '99999999';
        $applicants[1]['hiring_process'] = '004';
        $applicants[1]['role_id'] = '1';
        $applicants[1]['course_id'] = '1';
        $applicants[1]['pole_id'] = '1';

        $attributes = [];
        $attributes['applicants'] = $applicants;

        //execution
        $this->service->batchStore($attributes);

        //verifications
        $this->assertEquals('Bob Doe', Applicant::find(3)?->name);
        $this->assertEquals('mary@test4.com', Applicant::find(4)?->email);
    }
}
