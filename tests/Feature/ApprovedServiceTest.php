<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Approved;
use Mockery\MockInterface;
use App\Models\ApprovedState;
use App\Services\ApprovedService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\WithFaker;
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
        //verifications
        $this->assertEquals('John Doe', $this->service->list()->first()->name);
        $this->assertEquals(2, $this->service->list()->count());
    }

    /**
     * @test
     */
    public function approvedShouldBeDeleted()
    {
        //setting up scenario
        $approved = Approved::find(1);

        //execution 
        $this->service->delete($approved);

        //verifications
        $this->assertEquals('Jane Doe', $this->service->list()->first()->name);
        $this->assertEquals(1, $this->service->list()->count());
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

        //execution
        $this->service->changeState($attributes, $approved);

        //verifications
        $this->assertEquals('Foo', $approved->approvedState->name);
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
