<?php

namespace tests\Unit\app\Http\Controllers;

use App\Http\Controllers\UserController;
use App\Http\Requests\User\DestroyUserRequest;
use App\Http\Requests\User\IndexUserRequest;
use App\Http\Requests\User\ShowUserRequest;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\User;
use App\Services\Dto\UserDto;
use App\Services\UserService;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Pagination\LengthAwarePaginator;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;

final class UserControllerTest extends TestCase
{
    use WithoutMiddleware;

    /** @var UserController $controller */
    private UserController $controller;

    /** @var MockObject $serviceMock */
    private MockObject $serviceMock;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a Mock for the UserService class.
        $this->serviceMock = $this->createMock(UserService::class);

        $this->controller = new UserController($this->serviceMock);
    }

    public function testControllerIndexShouldCallServiceListMethod(): void
    {
        // Create a stub for the IndexUserRequest class.
        /** @var MockObject $requestStub */
        $requestStub = $this->createStub(IndexUserRequest::class);

        // Provides a fake LengthAwarePaginator to be returned by the service's list method.
        $lengthAwarePaginator = $this->createStub(LengthAwarePaginator::class);

        // Expects the service's list method to be called once and returns the fake LengthAwarePaginator.
        $this->serviceMock->expects($this->once())->method('list')
            ->willReturn($lengthAwarePaginator);

        $this->controller->index($requestStub);
    }

    public function testControllerStoreShouldCallServiceCreateMethod(): void
    {
        // Create a stub for the StoreUserRequest class.
        /** @var MockObject $requestStub */
        $requestStub = $this->createStub(StoreUserRequest::class);
        $requestStub->expects($this->any())->method('toDto')
            ->willReturn(new UserDto(
                login: '',
                password: '',
                active: true,
                employeeId: 1,
            ));

        // Expects the service's create method to be called once.
        $this->serviceMock->expects($this->once())->method('create');

        $this->controller->store($requestStub);
    }

    public function testControllerShowShouldCallServiceReadMethod(): void
    {
        // Create a stub for the ShowUserRequest class.
        /** @var MockObject $requestStub */
        $requestStub = $this->createStub(ShowUserRequest::class);

        /** @var MockObject $UserStub */
        $UserStub = $this->createStub(User::class);

        // Expects the service's changeState method to be called once.
        $this->serviceMock->expects($this->once())->method('read')
            ->willReturn($UserStub);

        $this->controller->show($requestStub, $UserStub);
    }

    public function testControllerUpdateShouldCallServiceUpdateMethod(): void
    {
        // Create a stub for the UpdateUserRequest class.
        /** @var MockObject $requestStub */
        $requestStub = $this->createStub(UpdateUserRequest::class);
        $requestStub->expects($this->any())->method('toDto')
            ->willReturn(new UserDto(
                login: '',
                password: '',
                active: true,
                employeeId: 1,
            ));

        /** @var MockObject $UserStub */
        $UserStub = $this->createStub(User::class);

        // Expects the service's changeState method to be called once.
        $this->serviceMock->expects($this->once())->method('update');

        $this->controller->update($requestStub, $UserStub);
    }

    public function testControllerDestroyShouldCallServiceDeleteMethod(): void
    {
        // Create a stub for the DestroyUserRequest class.
        /** @var MockObject $requestStub */
        $requestStub = $this->createStub(DestroyUserRequest::class);

        /** @var MockObject $UserStub */
        $UserStub = $this->createStub(User::class);

        // Expects the service's delete method to be called once.
        $this->serviceMock->expects($this->once())->method('delete');

        $this->controller->destroy($requestStub, $UserStub);
    }
}
