<?php

namespace tests\Unit\app\Http\Controllers;

use App\Http\Controllers\InstitutionalDetailController;
use App\Http\Requests\InstitutionalDetail\StoreInstitutionalDetailRequest;
use App\Http\Requests\InstitutionalDetail\UpdateInstitutionalDetailRequest;
use App\Models\Employee;
use App\Models\InstitutionalDetail;
use App\Services\InstitutionalDetailService;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;

class InstitutionalDetailControllerTest extends TestCase
{
    use WithoutMiddleware;

    /** @var InstitutionalDetailController $controller */
    private InstitutionalDetailController $controller;

    /** @var MockObject $serviceMock */
    private MockObject $serviceMock;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a Mock for the InstitutionalDetailService class.
        $this->serviceMock = $this->createMock(InstitutionalDetailService::class);

        $this->controller = new InstitutionalDetailController($this->serviceMock);
    }

    public function testControllerStoreShouldCallServiceCreateMethod(): void
    {
        // Create a stub for the StoreInstitutionalDetailRequest class.
        /** @var MockObject $requestStub */
        $requestStub = $this->createStub(StoreInstitutionalDetailRequest::class);

        // Expects the service's create method to be called once.
        $this->serviceMock->expects($this->once())->method('create');

        /** @var MockObject $employeeStub */
        $employeeStub = $this->createStub(Employee::class);
        $employeeStub->method('__get')->with('id')->willReturn(1);

        $this->controller->store($requestStub, $employeeStub);
    }

    public function testControllerUpdateShouldCallServiceUpdateMethod(): void
    {
        // Create a stub for the UpdateInstitutionalDetailRequest class.
        /** @var MockObject $requestStub */
        $requestStub = $this->createStub(UpdateInstitutionalDetailRequest::class);

        // Expects the service's changeState method to be called once.
        $this->serviceMock->expects($this->once())->method('update');

        /** @var MockObject $employeeStub */
        $employeeStub = $this->createStub(Employee::class);
        $employeeStub->method('__get')->will($this->returnValueMap(
            [
                ['id', 1],
                ['institutionalDetail', []],
            ]
        ));

        $this->controller->update($requestStub, $employeeStub);
    }
}
