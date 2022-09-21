<?php

namespace Tests\Unit;

use App\Http\Controllers\BondController;
use App\Http\Requests\Bond\DestroyBondRequest;
use App\Http\Requests\Bond\IndexBondRequest;
use App\Http\Requests\Bond\ShowBondRequest;
use App\Http\Requests\Bond\StoreBondRequest;
use App\Http\Requests\Bond\UpdateBondRequest;
use App\Models\Bond;
use App\Services\BondDocumentService;
use App\Services\BondService;
use App\Services\Dto\StoreBondDto;
use Illuminate\Database\Eloquent\Collection;
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

    /** @var MockObject $documentServiceStub */
    private MockObject $documentServiceStub;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a Mock for the BondService class.
        $this->serviceMock = $this->createMock(BondService::class);
        $this->documentServiceStub = $this->createMock(BondDocumentService::class);

        $this->controller = new BondController($this->serviceMock, $this->documentServiceStub);
    }

    public function testControllerIndexShouldCallServiceListMethod(): void
    {
        // Create a stub for the IndexBondRequest class.
        /** @var MockObject $requestStub */
        $requestStub = $this->createStub(IndexBondRequest::class);
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
        // Create a stub for the StoreBondDto class.
        /** @var MockObject $storeBondDto */
        $storeBondDto = $this->createStub(StoreBondDto::class);

        // Create a stub for the StoreBondRequest class.
        /** @var MockObject $requestStub */
        $requestStub = $this->createStub(StoreBondRequest::class);
        $requestStub->expects($this->any())->method('authorize')
            ->willReturn(true);
        $requestStub->expects($this->any())->method('rules')
            ->willReturn([]);
        $requestStub->expects($this->any())->method('toDto')
            ->willReturn($storeBondDto);

        // Expects the service's create method to be called once.
        $this->serviceMock->expects($this->once())->method('create');

        $this->controller->store($requestStub);
    }

    public function testControllerShowShouldCallServiceReadMethod(): void
    {
        // Create a stub for the ShowBondRequest class.
        /** @var MockObject $requestStub */
        $requestStub = $this->createStub(ShowBondRequest::class);
        $requestStub->expects($this->any())->method('authorize')
            ->willReturn(true);
        $requestStub->expects($this->any())->method('rules')
            ->willReturn([]);
        $requestStub->expects($this->any())->method('all')
            ->willReturn([]);

        /** @var MockObject $bondStub */
        $bondStub = $this->createStub(Bond::class);
        $bondStub->method('__get')->with('id')->willReturn(1);

        /** @var MockObject $eloquentCollectionStub */
        $eloquentCollectionStub = $this->createStub(Collection::class);

        $this->documentServiceStub->method('getByBond')->willReturn($eloquentCollectionStub);
        app()->instance(BondDocumentService::class, $this->documentServiceStub);

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
        $requestStub->expects($this->any())->method('authorize')
            ->willReturn(true);
        $requestStub->expects($this->any())->method('rules')
            ->willReturn([]);
        $requestStub->expects($this->any())->method('all')
            ->willReturn([]);

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
        $requestStub->expects($this->any())->method('authorize')
            ->willReturn(true);
        $requestStub->expects($this->any())->method('rules')
            ->willReturn([]);

        /** @var MockObject $bondStub */
        $bondStub = $this->createStub(Bond::class);

        // Expects the service's delete method to be called once.
        $this->serviceMock->expects($this->once())->method('delete');

        $this->controller->destroy($requestStub, $bondStub);
    }
}
