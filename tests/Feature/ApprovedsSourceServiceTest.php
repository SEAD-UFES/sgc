<?php

namespace Tests\Feature;

use App\Models\Approved;
use App\Services\ApprovedsSourceService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Mockery\MockInterface;
use Tests\TestCase;

class ApprovedsSourceServiceTest extends TestCase
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

        $this->service = new ApprovedsSourceService;
    }

    /**
     * @test
     */
    public function shouldImportApprovedsList()
    {
        //overwriting 'getApprovedsFromFile' method and asserting parameter
        $service = $this->partialMock(ApprovedsSourceService::class, function (MockInterface $service) {
            $service
                ->shouldAllowMockingProtectedMethods()
                ->shouldReceive('getApprovedsFromFile')->once()->with('temp/approvedsSpreadsheet.xlsx')->andReturn(Approved::all());
        });

        //setting up scenario
        Storage::fake('local');
        $fakeUploadedFile = UploadedFile::fake()->create('approvedsSpreadsheet.xlsx', 20, 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        //execution
        $service->importApprovedsFromFile($fakeUploadedFile);
    }
}
