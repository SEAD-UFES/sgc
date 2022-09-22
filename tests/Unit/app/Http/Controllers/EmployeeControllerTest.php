<?php

namespace tests\Unit\app\Http\Controllers;

use App\Http\Controllers\EmployeeController;
use App\Http\Requests\Employee\DestroyEmployeeRequest;
use App\Http\Requests\Employee\IndexEmployeeRequest;
use App\Http\Requests\Employee\ShowEmployeeRequest;
use App\Http\Requests\Employee\StoreEmployeeRequest;
use App\Http\Requests\Employee\UpdateEmployeeRequest;
use App\Models\Employee;
use App\Services\EmployeeService;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Pagination\LengthAwarePaginator;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;

class EmployeeControllerTest extends TestCase
{
    use WithoutMiddleware;

    /** @var EmployeeController $controller */
    private EmployeeController $controller;

    /** @var MockObject $serviceMock */
    private MockObject $serviceMock;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a Mock for the EmployeeService class.
        $this->serviceMock = $this->createMock(EmployeeService::class);

        $this->controller = new EmployeeController($this->serviceMock);
    }

    public function testControllerIndexShouldCallServiceListMethod(): void
    {
        // Create a stub for the IndexEmployeeRequest class.
        /** @var MockObject $requestStub */
        $requestStub = $this->createStub(IndexEmployeeRequest::class);

        // Provides a fake LengthAwarePaginator to be returned by the service's list method.
        $lengthAwarePaginator = $this->createStub(LengthAwarePaginator::class);

        // Expects the service's list method to be called once and returns the fake LengthAwarePaginator.
        $this->serviceMock->expects($this->once())->method('list')
            ->willReturn($lengthAwarePaginator);

        $this->controller->index($requestStub);
    }

    public function testControllerStoreShouldCallServiceCreateMethod(): void
    {
        // Create a stub for the StoreEmployeeRequest class.
        /** @var MockObject $requestStub */
        $requestStub = $this->createStub(StoreEmployeeRequest::class);

        // Expects the service's create method to be called once.
        $this->serviceMock->expects($this->once())->method('create');

        $this->controller->store($requestStub);
    }

    public function testControllerShowShouldCallServiceReadMethod(): void
    {
        // Create a stub for the ShowEmployeeRequest class.
        /** @var MockObject $requestStub */
        $requestStub = $this->createStub(ShowEmployeeRequest::class);

        /** @var MockObject $bondStub */
        $bondStub = $this->createStub(Employee::class);

        // Expects the service's changeState method to be called once.
        $this->serviceMock->expects($this->once())->method('read')
            ->willReturn($bondStub);

        $this->controller->show($requestStub, $bondStub);
    }

    public function testControllerUpdateShouldCallServiceUpdateMethod(): void
    {
        // Create a stub for the UpdateEmployeeRequest class.
        /** @var MockObject $requestStub */
        $requestStub = $this->createStub(UpdateEmployeeRequest::class);

        /** @var MockObject $bondStub */
        $bondStub = $this->createStub(Employee::class);

        // Expects the service's changeState method to be called once.
        $this->serviceMock->expects($this->once())->method('update');

        $this->controller->update($requestStub, $bondStub);
    }

    public function testControllerDestroyShouldCallServiceDeleteMethod(): void
    {
        // Create a stub for the DestroyEmployeeRequest class.
        /** @var MockObject $requestStub */
        $requestStub = $this->createStub(DestroyEmployeeRequest::class);

        /** @var MockObject $bondStub */
        $bondStub = $this->createStub(Employee::class);

        // Expects the service's delete method to be called once.
        $this->serviceMock->expects($this->once())->method('delete');

        $this->controller->destroy($requestStub, $bondStub);
    }
}
