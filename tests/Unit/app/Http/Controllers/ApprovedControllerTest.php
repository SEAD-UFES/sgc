<?php

namespace Tests\Unit;

use App\Http\Controllers\ApprovedController;
use App\Http\Requests\Approved\DestroyApprovedRequest;
use App\Http\Requests\Approved\IndexApprovedRequest;
use App\Http\Requests\Approved\StoreApprovedRequest;
use App\Http\Requests\Approved\UpdateApprovedStateRequest;
use App\Models\Approved;
use App\Services\ApprovedService;
use App\Services\Dto\StoreApprovedDto;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Pagination\LengthAwarePaginator;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;

class ApprovedControllerTest extends TestCase
{
    use WithoutMiddleware;

    /** @var ApprovedController $controller */
    private ApprovedController $controller;

    /** @var MockObject $serviceMock */
    private MockObject $serviceMock;

    public function setUp(): void
    {
        parent::setUp();

        // Create a Mock for the ApprovedService class.
        $this->serviceMock = $this->createMock(ApprovedService::class);

        $this->controller = new ApprovedController($this->serviceMock);
    }

    public function testControllerIndexShouldCallServiceListMethod(): void
    {
        // Create a stub for the IndexApprovedRequest class.
        /** @var MockObject $requestStub */
        $requestStub = $this->createStub(IndexApprovedRequest::class);
        $requestStub->expects($this->any())->method('authorize')
            ->willReturn(true);

        // Provides a fake LengthAwarePaginator to be returned by the service's list method.
        $lengthAwarePaginator = $this->createStub(LengthAwarePaginator::class);

        // Expects the service's list method to be called once and returns the fake LengthAwarePaginator.
        $this->serviceMock->expects($this->once())->method('list')
            ->willReturn($lengthAwarePaginator);

        $this->controller->index($requestStub);
    }

    public function testControllerStoreShouldCallServiceCreateMethod(): void
    {
        // Create a stub for the StoreApprovedDto class.
        /** @var MockObject $storeApprovedDto */
        $storeApprovedDto = $this->createStub(StoreApprovedDto::class);

        // Create a stub for the StoreApprovedRequest class.
        /** @var MockObject $requestStub */
        $requestStub = $this->createStub(StoreApprovedRequest::class);
        $requestStub->expects($this->any())->method('authorize')
            ->willReturn(true);
        $requestStub->expects($this->any())->method('rules')
            ->willReturn([]);
        $requestStub->expects($this->any())->method('toDto')
            ->willReturn($storeApprovedDto);

        // Expects the service's create method to be called once.
        $this->serviceMock->expects($this->once())->method('create');

        $this->controller->store($requestStub);
    }

    public function testControllerUpdateShouldCallServiceChangeStateMethod(): void
    {
        // Create a stub for the UpdateApprovedStateRequest class.
        /** @var MockObject $requestStub */
        $requestStub = $this->createStub(UpdateApprovedStateRequest::class);
        $requestStub->expects($this->any())->method('authorize')
            ->willReturn(true);
        $requestStub->expects($this->any())->method('rules')
            ->willReturn([]);
        $requestStub->expects($this->any())->method('all')
            ->willReturn([]);

        /** @var MockObject $approvedStub */
        $approvedStub = $this->createStub(Approved::class);

        // Expects the service's changeState method to be called once.
        $this->serviceMock->expects($this->once())->method('changeState');

        $this->controller->update($requestStub, $approvedStub);
    }

    public function testControllerDestroyShouldCallServiceDeleteMethod(): void
    {
        // Create a stub for the DestroyApprovedRequest class.
        /** @var MockObject $requestStub */
        $requestStub = $this->createStub(DestroyApprovedRequest::class);
        $requestStub->expects($this->any())->method('authorize')
            ->willReturn(true);
        $requestStub->expects($this->any())->method('rules')
            ->willReturn([]);

        /** @var MockObject $approvedStub */
        $approvedStub = $this->createStub(Approved::class);

        // Expects the service's delete method to be called once.
        $this->serviceMock->expects($this->once())->method('delete');

        $this->controller->destroy($requestStub, $approvedStub);
    }
}
