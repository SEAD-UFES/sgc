<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use App\Events\FileImported;
use App\Models\Applicant;
use App\Services\ApplicantsSourceService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use Mockery\MockInterface;
use Tests\TestCase;

final class ApplicantsSourceServiceTest extends TestCase
{
    use RefreshDatabase;

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
    }

    #[Test]
    public function shouldImportApplicantsList(): void
    {
        Event::fake();

        //setting up scenario
        Storage::fake('local');
        $fakeUploadedFile = UploadedFile::fake()->create('applicantsSpreadsheet.xlsx', 20, 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        //overwriting 'importApplicantsFromSpreadsheet' method and asserting parameter
        /** @var MockInterface */
        $mockedService = $this->partialMock(ApplicantsSourceService::class, function (MockInterface $mock) {
            $mock->shouldReceive('importApplicantsFromSpreadsheet')->once();
        });

        //execution
        $mockedService->readSourceSpreadsheet($fakeUploadedFile);

        Event::assertDispatched(FileImported::class);
    }
}
