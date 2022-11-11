<?php

namespace Tests\Feature;

use App\Models\Applicant;
use App\Services\ApplicantsSourceService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Mockery\MockInterface;
use Tests\TestCase;

class ApplicantsSourceServiceTest extends TestCase
{
    use RefreshDatabase;

    //setting up scenario for all tests
    public function setUp(): void
    {
        parent::setUp();

        Applicant::factory()->create(
            [
                'name' => 'John Doe',
                'email' => 'john@test.com',
                'area_code' => '01',
                'phone' => '12345678',
                'mobile' => '123456789',
                'hiring_process' => '001',
            ]
        );

        Applicant::factory()->create(
            [
                'name' => 'Jane Doe',
                'email' => 'jane@othertest.com',
                'area_code' => '02',
                'phone' => '01234567',
                'mobile' => '012345678',
                'hiring_process' => '002',
            ]
        );

        $this->service = new ApplicantsSourceService();
    }

    /**
     * @test
     */
    public function shouldImportApplicantsList()
    {
        //overwriting 'getApplicantsFromFile' method and asserting parameter
        $service = $this->partialMock(ApplicantsSourceService::class, function (MockInterface $service) {
            $service
                ->shouldAllowMockingProtectedMethods()
                ->shouldReceive('getApplicantsFromFile')->once()->with('temp/applicantsSpreadsheet.xlsx')->andReturn(Applicant::all());
        });

        //setting up scenario
        Storage::fake('local');
        $fakeUploadedFile = UploadedFile::fake()->create('applicantsSpreadsheet.xlsx', 20, 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        //execution
        $service->importApplicantsFromFile($fakeUploadedFile);
    }
}
