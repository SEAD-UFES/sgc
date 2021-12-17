<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseType;
use Illuminate\Http\Request;
use App\Services\CourseService;
use Illuminate\Support\Facades\Gate;
use App\CustomClasses\ModelFilterHelpers;
use App\Http\Requests\StoreCourseRequest;
use App\Http\Requests\UpdateCourseRequest;

class CourseController extends Controller
{
    public function __construct(CourseService $courseService)
    {
        $this->service = $courseService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //check access permission
        if (!Gate::allows('course-list')) {
            return response()->view('access.denied')->setStatusCode(401);
        }

        //filters
        $filters = ModelFilterHelpers::buildFilters($request, Course::$accepted_filters);

        $courses = $this->service->list();

        return view('course.index', compact('courses', 'filters'))->with('i', (request()->input('page', 1) - 1) * 10);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //check access permission
        if (!Gate::allows('course-store')) {
            return response()->view('access.denied')->setStatusCode(401);
        }

        $courseTypes = CourseType::orderBy('name')->get();

        return view('course.create', compact('courseTypes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreCourseRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCourseRequest $request)
    {
        //check access permission
        if (!Gate::allows('course-store')) {
            return response()->view('access.denied')->setStatusCode(401);
        }

        $this->service->create($request->validated());

        return redirect()->route('courses.index')->with('success', 'Curso criado com sucesso.');
    }

    /**
     * Display the specified resource.
     *
     * @param Course $course
     * @return \Illuminate\Http\Response
     */
    public function show(Course $course)
    {
        //check access permission
        if (!Gate::allows('course-show')) {
            return response()->view('access.denied')->setStatusCode(401);
        }

        $this->service->read($course);

        return view('course.show', compact('course'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Course $course
     * @return \Illuminate\Http\Response
     */
    public function edit(Course $course)
    {
        //check access permission
        if (!Gate::allows('course-update')) {
            return response()->view('access.denied')->setStatusCode(401);
        }

        $courseTypes = CourseType::orderBy('name')->get();

        return view('course.edit', compact('course', 'courseTypes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateCourseRequest $request
     * @param Course $course
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCourseRequest $request, Course $course)
    {
        //check access permission
        if (!Gate::allows('course-update')) {
            return response()->view('access.denied')->setStatusCode(401);
        }

        try {
            $this->service->update($request->validated(), $course);
        } catch (\Exception $e) {
            return back()->withErrors(['noStore' => 'Não foi possível salvar o curso: ' . $e->getMessage()]);
        }

        return redirect()->route('courses.index')->with('success', 'Curso atualizado com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Course $course
     * @return \Illuminate\Http\Response
     */
    public function destroy(Course $course)
    {
        //check access permission
        if (!Gate::allows('course-destroy')) {
            return response()->view('access.denied')->setStatusCode(401);
        }

        try {
            $this->service->delete($course);
        } catch (\Exception $e) {
            return back()->withErrors(['noDestroy' => 'Não foi possível excluir o curso: ' . $e->getMessage()]);
        }

        return redirect()->route('courses.index')->with('success', 'Curso excluído com sucesso.');
    }
}
