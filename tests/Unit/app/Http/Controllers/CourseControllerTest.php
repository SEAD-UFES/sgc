<?php

namespace tests\Unit\app\Http\Controllers;

use App\Enums\Degrees;
use App\Http\Controllers\CourseController;
use App\Http\Requests\Course\DestroyCourseRequest;
use App\Http\Requests\Course\IndexCourseRequest;
use App\Http\Requests\Course\ShowCourseRequest;
use App\Http\Requests\Course\StoreCourseRequest;
use App\Http\Requests\Course\UpdateCourseRequest;
use App\Models\Course;
use App\Services\CourseService;
use App\Services\Dto\CourseDto;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Pagination\LengthAwarePaginator;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;

final class CourseControllerTest extends TestCase
{
    use WithoutMiddleware;

    /** @var CourseController $controller */
    private CourseController $controller;

    /** @var MockObject $serviceMock */
    private MockObject $serviceMock;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a Mock for the CourseService class.
        $this->serviceMock = $this->createMock(CourseService::class);

        $this->controller = new CourseController($this->serviceMock);
    }

    public function testControllerIndexShouldCallServiceListMethod(): void
    {
        // Create a stub for the IndexCourseRequest class.
        /** @var MockObject $requestStub */
        $requestStub = $this->createStub(IndexCourseRequest::class);

        // Provides a fake LengthAwarePaginator to be returned by the service's list method.
        $lengthAwarePaginator = $this->createStub(LengthAwarePaginator::class);

        // Expects the service's list method to be called once and returns the fake LengthAwarePaginator.
        $this->serviceMock->expects($this->once())->method('list')
            ->willReturn($lengthAwarePaginator);

        $this->controller->index($requestStub);
    }

    public function testControllerStoreShouldCallServiceCreateMethod(): void
    {
        // Create a stub for the StoreCourseRequest class.
        /** @var MockObject $requestStub */
        $requestStub = $this->createStub(StoreCourseRequest::class);
        $requestStub->expects($this->any())->method('toDto')
            ->willReturn(new CourseDto(
                name: '',
                description: '',
                degree: Degrees::B,
                lmsUrl: '',
            ));

        // Expects the service's create method to be called once.
        $this->serviceMock->expects($this->once())->method('create');

        $this->controller->store($requestStub);
    }

    public function testControllerUpdateShouldCallServiceUpdateMethod(): void
    {
        // Create a stub for the UpdateCourseRequest class.
        /** @var MockObject $requestStub */
        $requestStub = $this->createStub(UpdateCourseRequest::class);
        $requestStub->expects($this->any())->method('toDto')
            ->willReturn(new CourseDto(
                name: '',
                description: '',
                degree: Degrees::B,
                lmsUrl: '',
            ));

        /** @var MockObject $bondStub */
        $bondStub = $this->createStub(Course::class);

        // Expects the service's changeState method to be called once.
        $this->serviceMock->expects($this->once())->method('update');

        $this->controller->update($requestStub, $bondStub);
    }

    public function testControllerDestroyShouldCallServiceDeleteMethod(): void
    {
        // Create a stub for the DestroyCourseRequest class.
        /** @var MockObject $requestStub */
        $requestStub = $this->createStub(DestroyCourseRequest::class);

        /** @var MockObject $bondStub */
        $bondStub = $this->createStub(Course::class);

        // Expects the service's delete method to be called once.
        $this->serviceMock->expects($this->once())->method('delete');

        $this->controller->destroy($requestStub, $bondStub);
    }
}
