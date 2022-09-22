<?php

namespace tests\Unit\app\Http\Controllers;

use App\Http\Controllers\BondController;
use App\Http\Requests\Bond\DestroyBondRequest;
use App\Http\Requests\Bond\IndexBondRequest;
use App\Http\Requests\Bond\ShowBondRequest;
use App\Http\Requests\Bond\StoreBondRequest;
use App\Http\Requests\Bond\UpdateBondRequest;
use App\Models\Bond;
use App\Services\BondService;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Pagination\LengthAwarePaginator;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;

class BondControllerTest extends TestCase
{
    use WithoutMiddleware;

    /** @var BondController $controller */
    private BondController $controller;

    /** @var MockObject $serviceMock */
    private MockObject $serviceMock;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a Mock for the BondService class.
        $this->serviceMock = $this->createMock(BondService::class);

        $this->controller = new BondController($this->serviceMock);
    }

    public function testControllerIndexShouldCallServiceListMethod(): void
    {
        // Create a stub for the IndexBondRequest class.
        /** @var MockObject $requestStub */
        $requestStub = $this->createStub(IndexBondRequest::class);

        // Provides a fake LengthAwarePaginator to be returned by the service's list method.
        $lengthAwarePaginator = $this->createStub(LengthAwarePaginator::class);

        // Expects the service's list method to be called once and returns the fake LengthAwarePaginator.
        $this->serviceMock->expects($this->once())->method('list')
            ->willReturn($lengthAwarePaginator);

        $this->controller->index($requestStub);
    }

    public function testControllerStoreShouldCallServiceCreateMethod(): void
    {
        // Create a stub for the StoreBondRequest class.
        /** @var MockObject $requestStub */
        $requestStub = $this->createStub(StoreBondRequest::class);

        // Expects the service's create method to be called once.
        $this->serviceMock->expects($this->once())->method('create');

        $this->controller->store($requestStub);
    }

    public function testControllerShowShouldCallServiceReadMethod(): void
    {
        // Create a stub for the ShowBondRequest class.
        /** @var MockObject $requestStub */
        $requestStub = $this->createStub(ShowBondRequest::class);

        /** @var MockObject $bondStub */
        $bondStub = $this->createStub(Bond::class);

        // Expects the service's changeState method to be called once.
        $this->serviceMock->expects($this->once())->method('read')
            ->willReturn($bondStub);

        $this->controller->show($requestStub, $bondStub);
    }

    public function testControllerUpdateShouldCallServiceUpdateMethod(): void
    {
        // Create a stub for the UpdateBondRequest class.
        /** @var MockObject $requestStub */
        $requestStub = $this->createStub(UpdateBondRequest::class);

        /** @var MockObject $bondStub */
        $bondStub = $this->createStub(Bond::class);

        // Expects the service's changeState method to be called once.
        $this->serviceMock->expects($this->once())->method('update');

        $this->controller->update($requestStub, $bondStub);
    }

    public function testControllerDestroyShouldCallServiceDeleteMethod(): void
    {
        // Create a stub for the DestroyBondRequest class.
        /** @var MockObject $requestStub */
        $requestStub = $this->createStub(DestroyBondRequest::class);

        /** @var MockObject $bondStub */
        $bondStub = $this->createStub(Bond::class);

        // Expects the service's delete method to be called once.
        $this->serviceMock->expects($this->once())->method('delete');

        $this->controller->destroy($requestStub, $bondStub);
    }
}
