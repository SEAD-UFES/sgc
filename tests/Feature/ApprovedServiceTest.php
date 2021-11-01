<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Approved;
use App\Models\Employee;
use Mockery\MockInterface;
use App\Models\ApprovedState;
use App\Services\ApprovedService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\WithFaker;
use App\Exceptions\EmployeeAlreadyExistsException;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApprovedServiceTest extends TestCase
{
    use RefreshDatabase;

    //setting up scenario for all tests
    public function setUp(): void
    {
        parent::setUp();

        Approved::factory()->create(
            [
                'name' => 'John Doe',
                'email' => 'john@test.com',
                'area_code' => '01',
                'phone' => '12345678',
                'mobile' => '123456789',
                'announcement' => '001',
            ]
        );

        Approved::factory()->create(
            [
                'name' => 'Jane Doe',
                'email' => 'jane@othertest.com',
                'area_code' => '02',
                'phone' => '01234567',
                'mobile' => '012345678',
                'announcement' => '002',
            ]
        );

        $this->service = new ApprovedService;
    }

    /**
     * @test
     */
    public function approvedsShouldBeListed()
    {
        Event::fakeFor(function () {
            //execution 
            $approveds = $this->service->list();

            //verifications
            Event::assertDispatched('eloquent.listed: ' . Approved::class);
            $this->assertCount(2, $approveds);
            $this->assertEquals('John Doe', $approveds[0]->name);
        });
    }

    /**
     * @test
     */
    public function approvedShouldBeRetrieved()
    {
        //setting up scenario
        $approved = Approved::find(1);

        Event::fakeFor(function () use ($approved) {
            //execution 
            $approved = $this->service->read($approved);

            //verifications
            Event::assertDispatched('eloquent.fetched: ' . Approved::class);
            $this->assertEquals('John Doe', $approved->name);
            $this->assertCount(2, Approved::all());
        });
    }

    /**
     * @test
     */
    public function approvedShouldBeDeleted()
    {
        //setting up scenario
        $approved = Approved::find(1);

        Event::fakeFor(function () use ($approved) {
            //execution 
            $approved = $this->service->delete($approved);

            //verifications
            Event::assertDispatched('eloquent.deleted: ' . Approved::class);
            $this->assertEquals('Jane Doe', $this->service->list()->first()->name);
            $this->assertCount(1, Approved::all());
        });
    }

    /**
     * @test
     */
    public function approvedStateShouldChange()
    {
        //setting up scenario
        $state_id = ApprovedState::factory()->create(
            [
                'name' => 'Foo',
                'description' => 'Bar',
            ]
        )->id;
        $attributes['states'] = $state_id;
        $approved = Approved::find(1);

        Event::fakeFor(function () use ($approved, $attributes) {
            //execution 
            $this->service->changeState($attributes, $approved);

            //verifications
            Event::assertDispatched('eloquent.saved: ' . Approved::class);
            $this->assertEquals('Foo', $approved->approvedState->name);
            $this->assertCount(2, Approved::all());
        });
    }

    /**
     * @test
     */
    public function approvedShouldBecomesAnEmployee()
    {
        //setting up scenario
        $approved = Approved::find(1);

        //execution
        $employee = $this->service->designate($approved);

        //verifications
        $this->assertEquals('App\Models\Employee', get_class($employee));
        $this->assertEquals('John Doe', $employee->name);
    }

    /**
     * @test
     */
    public function approvedShouldNotBecomesAnPreexistingEmployee()
    {
        //setting up scenario
        $approved = Approved::find(1);

        Employee::factory()->create(
            [
                'name' => 'Bob Doe',
                'email' => 'john@test.com'
            ]
        );

        $this->expectException(EmployeeAlreadyExistsException::class);

        //execution
        $this->service->designate($approved);

        //verifications
        $this->assertCount(1, Employee::all());
    }

    /**
     * @test
     */
    public function shouldImportApprovedsList()
    {
        //overwriting 'getApprovedsFromFile' method and asserting parameter
        $service = $this->partialMock(ApprovedService::class, function (MockInterface $service) {
            $service
                ->shouldAllowMockingProtectedMethods()
                ->shouldReceive('getApprovedsFromFile')->once()->with('temp/approvedsSpreadsheet.xlsx')->andReturn(Approved::all());
        });

        //setting up scenario
        Storage::fake('local');
        $fakeUploadedFile = UploadedFile::fake()->create('approvedsSpreadsheet.xlsx', 20, 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        //execution
        $approveds = $service->importApproveds($fakeUploadedFile);
    }

    /**
     * @test
     */
    public function shouldPersistApprovedsList()
    {
        //setting up scenario
        $approvedsArray = array();

        $approvedsArray['check_0'] = true;
        $approvedsArray['name_0'] = 'Bob Doe';
        $approvedsArray['email_0'] = 'bob@test3.com';
        $approvedsArray['announcement_0'] = '003';

        $approvedsArray['check_1'] = true;
        $approvedsArray['name_1'] = 'Mary Doe';
        $approvedsArray['email_1'] = 'mary@test4.com';
        $approvedsArray['announcement_1'] = '004';

        $approvedsArray['approvedsCount'] = 2;

        //execution
        $this->service->massStore($approvedsArray);

        //verifications
        $this->assertEquals('Bob Doe', Approved::find(3)->name);
        $this->assertEquals('mary@test4.com', Approved::find(4)->email);
    }
}
