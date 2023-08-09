<?php

namespace tests\Unit\app\Http\Controllers;

use App\Http\Controllers\ApplicantController;
use App\Http\Requests\Applicant\DestroyApplicantRequest;
use App\Http\Requests\Applicant\IndexApplicantRequest;
use App\Http\Requests\Applicant\StoreApplicantRequest;
use App\Http\Requests\Applicant\UpdateApplicantStateRequest;
use App\Models\Applicant;
use App\Services\ApplicantService;
use App\Services\Dto\ApplicantDto;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Pagination\LengthAwarePaginator;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;

final class ApplicantControllerTest extends TestCase
{
    use WithoutMiddleware;

    /** @var ApplicantController $controller */
    private ApplicantController $controller;

    /** @var MockObject $serviceMock */
    private MockObject $serviceMock;

    public function __construct()
    {
        parent::__construct();
        $this->serviceMock = $this->createMock(ApplicantService::class);
        $this->controller = new ApplicantController($this->serviceMock);
    }

    // public function setUp(): void
    // {
    //     parent::setUp();

    //     // Create a Mock for the ApplicantService class.
    //     $this->serviceMock = $this->createMock(ApplicantService::class);

    //     $this->controller = new ApplicantController($this->serviceMock);
    // }

    public function testControllerIndexShouldCallServiceListMethod(): void
    {
        // Create a stub for the IndexApplicantRequest class.
        /** @var MockObject $requestStub */
        $requestStub = $this->createStub(IndexApplicantRequest::class);

        // Provides a fake LengthAwarePaginator to be returned by the service's list method.
        $lengthAwarePaginator = $this->createStub(LengthAwarePaginator::class);

        // Expects the service's list method to be called once and returns the fake LengthAwarePaginator.
        $this->serviceMock->expects($this->once())->method('list')
            ->willReturn($lengthAwarePaginator);

        $this->controller->index($requestStub);
    }

    public function testControllerStoreShouldCallServiceCreateMethod(): void
    {
        // Create a stub for the StoreApplicantRequest class.
        /** @var MockObject $requestStub */
        $requestStub = $this->createStub(StoreApplicantRequest::class);
        $requestStub->expects($this->any())->method('toDto')
            ->willReturn(new ApplicantDto(
                name: 'John Doe',
                email: 'jhondoe@mail.com',
                areaCode: '27',
                landline: '2733371234',
                mobile: '27996121234',
                hiringProcess: '69/2023',
                roleId: 1,
                courseId: null,
                poleId: null,
            ));

        // Expects the service's create method to be called once.
        $this->serviceMock->expects($this->once())->method('create');

        $this->controller->store($requestStub);
    }

    public function testControllerUpdateShouldCallServiceChangeStateMethod(): void
    {
        // Create a stub for the UpdateApplicantStateRequest class.
        /** @var MockObject $requestStub */
        $requestStub = $this->createStub(UpdateApplicantStateRequest::class);
        $requestStub->expects($this->any())->method('all')
            ->willReturn([]);

        /** @var MockObject $applicantStub */
        $applicantStub = $this->createStub(Applicant::class);

        // Expects the service's changeState method to be called once.
        $this->serviceMock->expects($this->once())->method('changeState');

        $this->controller->update($requestStub, $applicantStub);
    }

    public function testControllerDestroyShouldCallServiceDeleteMethod(): void
    {
        // Create a stub for the DestroyApplicantRequest class.
        /** @var MockObject $requestStub */
        $requestStub = $this->createStub(DestroyApplicantRequest::class);

        /** @var MockObject $applicantStub */
        $applicantStub = $this->createStub(Applicant::class);

        // Expects the service's delete method to be called once.
        $this->serviceMock->expects($this->once())->method('delete');

        $this->controller->destroy($requestStub, $applicantStub);
    }
}
