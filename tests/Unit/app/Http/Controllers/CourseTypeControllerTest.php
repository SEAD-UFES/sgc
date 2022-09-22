<?php

namespace tests\Unit\app\Http\Controllers;

use App\Http\Controllers\CourseTypeController;
use App\Services\CourseTypeService;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;

class CourseTypeControllerTest extends TestCase
{
    use WithoutMiddleware;

    /** @var CourseTypeController $controller */
    private CourseTypeController $controller;

    /** @var MockObject $serviceMock */
    private MockObject $serviceMock;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a Mock for the CourseTypeService class.
        $this->serviceMock = $this->createMock(CourseTypeService::class);

        $this->controller = new CourseTypeController($this->serviceMock);
    }

    public function testControllerIndexShouldCallServiceListMethod(): void
    {
        // Create a stub for the IndexCourseTypeRequest class.
        /** @var MockObject $requestStub */
        $requestStub = $this->createStub(Request::class);

        // Provides a fake LengthAwarePaginator to be returned by the service's list method.
        $lengthAwarePaginator = $this->createStub(LengthAwarePaginator::class);

        // Expects the service's list method to be called once and returns the fake LengthAwarePaginator.
        $this->serviceMock->expects($this->once())->method('list')
            ->willReturn($lengthAwarePaginator);

        $this->controller->index($requestStub);
    }
}
