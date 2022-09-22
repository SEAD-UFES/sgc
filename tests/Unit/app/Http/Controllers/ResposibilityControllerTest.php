<?php

namespace tests\Unit\app\Http\Controllers;

use App\Http\Controllers\ResponsibilityController;
use App\Http\Requests\Responsibility\DestroyResponsibilityRequest;
use App\Http\Requests\Responsibility\IndexResponsibilityRequest;
use App\Http\Requests\Responsibility\ShowResponsibilityRequest;
use App\Http\Requests\Responsibility\StoreResponsibilityRequest;
use App\Http\Requests\Responsibility\UpdateResponsibilityRequest;
use App\Models\Responsibility;
use App\Services\ResponsibilityService;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Pagination\LengthAwarePaginator;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;

class ResponsibilityControllerTest extends TestCase
{
    use WithoutMiddleware;

    /** @var ResponsibilityController $controller */
    private ResponsibilityController $controller;

    /** @var MockObject $serviceMock */
    private MockObject $serviceMock;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a Mock for the ResponsibilityService class.
        $this->serviceMock = $this->createMock(ResponsibilityService::class);

        $this->controller = new ResponsibilityController($this->serviceMock);
    }

    public function testControllerIndexShouldCallServiceListMethod(): void
    {
        // Create a stub for the IndexResponsibilityRequest class.
        /** @var MockObject $requestStub */
        $requestStub = $this->createStub(IndexResponsibilityRequest::class);

        // Provides a fake LengthAwarePaginator to be returned by the service's list method.
        $lengthAwarePaginator = $this->createStub(LengthAwarePaginator::class);

        // Expects the service's list method to be called once and returns the fake LengthAwarePaginator.
        $this->serviceMock->expects($this->once())->method('list')
            ->willReturn($lengthAwarePaginator);

        $this->controller->index($requestStub);
    }

    public function testControllerStoreShouldCallServiceCreateMethod(): void
    {
        // Create a stub for the StoreResponsibilityRequest class.
        /** @var MockObject $requestStub */
        $requestStub = $this->createStub(StoreResponsibilityRequest::class);

        // Expects the service's create method to be called once.
        $this->serviceMock->expects($this->once())->method('create');

        $this->controller->store($requestStub);
    }

    public function testControllerShowShouldCallServiceReadMethod(): void
    {
        // Create a stub for the ShowResponsibilityRequest class.
        /** @var MockObject $requestStub */
        $requestStub = $this->createStub(ShowResponsibilityRequest::class);

        /** @var MockObject $ResponsibilityStub */
        $ResponsibilityStub = $this->createStub(Responsibility::class);

        // Expects the service's changeState method to be called once.
        $this->serviceMock->expects($this->once())->method('read')
            ->willReturn($ResponsibilityStub);

        $this->controller->show($requestStub, $ResponsibilityStub);
    }

    public function testControllerUpdateShouldCallServiceUpdateMethod(): void
    {
        // Create a stub for the UpdateResponsibilityRequest class.
        /** @var MockObject $requestStub */
        $requestStub = $this->createStub(UpdateResponsibilityRequest::class);

        /** @var MockObject $ResponsibilityStub */
        $ResponsibilityStub = $this->createStub(Responsibility::class);

        // Expects the service's changeState method to be called once.
        $this->serviceMock->expects($this->once())->method('update');

        $this->controller->update($requestStub, $ResponsibilityStub);
    }

    public function testControllerDestroyShouldCallServiceDeleteMethod(): void
    {
        // Create a stub for the DestroyResponsibilityRequest class.
        /** @var MockObject $requestStub */
        $requestStub = $this->createStub(DestroyResponsibilityRequest::class);

        /** @var MockObject $ResponsibilityStub */
        $ResponsibilityStub = $this->createStub(Responsibility::class);

        // Expects the service's delete method to be called once.
        $this->serviceMock->expects($this->once())->method('delete');

        $this->controller->destroy($requestStub, $ResponsibilityStub);
    }
}
