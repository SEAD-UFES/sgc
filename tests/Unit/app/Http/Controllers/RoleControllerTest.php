<?php

namespace tests\Unit\app\Http\Controllers;

use App\Enums\GrantTypes;
use App\Http\Controllers\RoleController;
use App\Http\Requests\Role\DestroyRoleRequest;
use App\Http\Requests\Role\IndexRoleRequest;
use App\Http\Requests\Role\ShowRoleRequest;
use App\Http\Requests\Role\StoreRoleRequest;
use App\Http\Requests\Role\UpdateRoleRequest;
use App\Models\Role;
use App\Services\Dto\RoleDto;
use App\Services\RoleService;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Pagination\LengthAwarePaginator;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;

final class RoleControllerTest extends TestCase
{
    use WithoutMiddleware;

    /** @var RoleController $controller */
    private RoleController $controller;

    /** @var MockObject $serviceMock */
    private MockObject $serviceMock;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a Mock for the RoleService class.
        $this->serviceMock = $this->createMock(RoleService::class);

        $this->controller = new RoleController($this->serviceMock);
    }

    public function testControllerIndexShouldCallServiceListMethod(): void
    {
        // Create a stub for the IndexRoleRequest class.
        /** @var MockObject $requestStub */
        $requestStub = $this->createStub(IndexRoleRequest::class);

        // Provides a fake LengthAwarePaginator to be returned by the service's list method.
        $lengthAwarePaginator = $this->createStub(LengthAwarePaginator::class);

        // Expects the service's list method to be called once and returns the fake LengthAwarePaginator.
        $this->serviceMock->expects($this->once())->method('list')
            ->willReturn($lengthAwarePaginator);

        $this->controller->index($requestStub);
    }

    public function testControllerStoreShouldCallServiceCreateMethod(): void
    {
        // Create a stub for the StoreRoleRequest class.
        /** @var MockObject $requestStub */
        $requestStub = $this->createStub(StoreRoleRequest::class);
        $requestStub->expects($this->any())->method('toDto')
            ->willReturn(new RoleDto(
                name: '',
                description: '',
                grantValue: 0,
                grantType: GrantTypes::M,
            ));

        // Expects the service's create method to be called once.
        $this->serviceMock->expects($this->once())->method('create');

        $this->controller->store($requestStub);
    }

    public function testControllerUpdateShouldCallServiceUpdateMethod(): void
    {
        // Create a stub for the UpdateRoleRequest class.
        /** @var MockObject $requestStub */
        $requestStub = $this->createStub(UpdateRoleRequest::class);
        $requestStub->expects($this->any())->method('toDto')
            ->willReturn(new RoleDto(
                name: '',
                description: '',
                grantValue: 0,
                grantType: GrantTypes::M,
            ));

        /** @var MockObject $RoleStub */
        $RoleStub = $this->createStub(Role::class);

        // Expects the service's changeState method to be called once.
        $this->serviceMock->expects($this->once())->method('update');

        $this->controller->update($requestStub, $RoleStub);
    }

    public function testControllerDestroyShouldCallServiceDeleteMethod(): void
    {
        // Create a stub for the DestroyRoleRequest class.
        /** @var MockObject $requestStub */
        $requestStub = $this->createStub(DestroyRoleRequest::class);

        /** @var MockObject $RoleStub */
        $RoleStub = $this->createStub(Role::class);

        // Expects the service's delete method to be called once.
        $this->serviceMock->expects($this->once())->method('delete');

        $this->controller->destroy($requestStub, $RoleStub);
    }
}
