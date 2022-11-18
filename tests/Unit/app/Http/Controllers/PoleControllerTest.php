<?php

namespace tests\Unit\app\Http\Controllers;

use App\Http\Controllers\PoleController;
use App\Http\Requests\Pole\DestroyPoleRequest;
use App\Http\Requests\Pole\IndexPoleRequest;
use App\Http\Requests\Pole\ShowPoleRequest;
use App\Http\Requests\Pole\StorePoleRequest;
use App\Http\Requests\Pole\UpdatePoleRequest;
use App\Models\Pole;
use App\Services\Dto\PoleDto;
use App\Services\PoleService;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Pagination\LengthAwarePaginator;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;

class PoleControllerTest extends TestCase
{
    use WithoutMiddleware;

    /** @var PoleController $controller */
    private PoleController $controller;

    /** @var MockObject $serviceMock */
    private MockObject $serviceMock;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a Mock for the PoleService class.
        $this->serviceMock = $this->createMock(PoleService::class);

        $this->controller = new PoleController($this->serviceMock);
    }

    public function testControllerIndexShouldCallServiceListMethod(): void
    {
        // Create a stub for the IndexPoleRequest class.
        /** @var MockObject $requestStub */
        $requestStub = $this->createStub(IndexPoleRequest::class);

        // Provides a fake LengthAwarePaginator to be returned by the service's list method.
        $lengthAwarePaginator = $this->createStub(LengthAwarePaginator::class);

        // Expects the service's list method to be called once and returns the fake LengthAwarePaginator.
        $this->serviceMock->expects($this->once())->method('list')
            ->willReturn($lengthAwarePaginator);

        $this->controller->index($requestStub);
    }

    public function testControllerStoreShouldCallServiceCreateMethod(): void
    {
        // Create a stub for the StorePoleRequest class.
        /** @var MockObject $requestStub */
        $requestStub = $this->createStub(StorePoleRequest::class);
        $requestStub->expects($this->any())->method('toDto')
            ->willReturn(new PoleDto(
                name: '',
                description: '',
            ));

        // Expects the service's create method to be called once.
        $this->serviceMock->expects($this->once())->method('create');

        $this->controller->store($requestStub);
    }

    public function testControllerUpdateShouldCallServiceUpdateMethod(): void
    {
        // Create a stub for the UpdatePoleRequest class.
        /** @var MockObject $requestStub */
        $requestStub = $this->createStub(UpdatePoleRequest::class);
        $requestStub->expects($this->any())->method('toDto')
            ->willReturn(new PoleDto(
                name: '',
                description: '',
            ));

        /** @var MockObject $PoleStub */
        $PoleStub = $this->createStub(Pole::class);

        // Expects the service's changeState method to be called once.
        $this->serviceMock->expects($this->once())->method('update');

        $this->controller->update($requestStub, $PoleStub);
    }

    public function testControllerDestroyShouldCallServiceDeleteMethod(): void
    {
        // Create a stub for the DestroyPoleRequest class.
        /** @var MockObject $requestStub */
        $requestStub = $this->createStub(DestroyPoleRequest::class);

        /** @var MockObject $PoleStub */
        $PoleStub = $this->createStub(Pole::class);

        // Expects the service's delete method to be called once.
        $this->serviceMock->expects($this->once())->method('delete');

        $this->controller->destroy($requestStub, $PoleStub);
    }
}
