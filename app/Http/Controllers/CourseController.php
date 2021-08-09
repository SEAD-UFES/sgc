<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseType;
use App\Http\Requests\StoreCourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use App\CustomClasses\SgcLogger;
use Illuminate\Http\Request;
use App\CustomClasses\ModelFilterHelpers;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $courses_query = new Course();

        //filters
        $filters = ModelFilterHelpers::buildFilters($request, Course::$accepted_filters);
        $courses_query = $courses_query->AcceptRequest(Course::$accepted_filters)->filter();

        //sort
        $courses_query = $courses_query->sortable(['name' => 'asc'])->with('courseType');

        //get paginate and add querystring on paginate links
        $courses = $courses_query->paginate(10);
        $courses->appends($request->all());

        //write on log
        SgcLogger::writeLog('Course');

        return view('course.index', compact('courses', 'filters'))->with('i', (request()->input('page', 1) - 1) * 10);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $courseTypes = CourseType::orderBy('name')->get();
        $course = new Course;

        SgcLogger::writeLog('Course');

        return view('course.create', compact('courseTypes', 'course'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCourseRequest $request)
    {
        $course = new Course;

        $course->name = $request->name;
        $course->description = $request->description;
        $course->course_type_id = $request->courseTypes;
        $course->begin = $request->begin;
        $course->end = $request->end;

        $course->save();

        SgcLogger::writeLog($course);

        return redirect()->route('courses.index')->with('success', 'Curso criado com sucesso.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function show(Course $course)
    {
        return view('course.show', compact('course'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function edit(Course $course)
    {
        $courseTypes = CourseType::orderBy('name')->get();

        SgcLogger::writeLog($course);

        return view('course.edit', compact('course', 'courseTypes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCourseRequest $request, Course $course)
    {
        $course->name = $request->name;
        $course->description =  $request->description;
        $course->course_type_id = $request->courseTypes;
        $course->begin = $request->begin;
        $course->end = $request->end;

        try {
            $course->save();
        } catch (\Exception $e) {
            return back()->withErrors(['noStore' => 'Não foi possível salvar o curso: ' . $e->getMessage()]);
        }

        SgcLogger::writeLog($course);

        return redirect()->route('courses.index')->with('success', 'Curso atualizado com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function destroy(Course $course)
    {
        SgcLogger::writeLog($course);
        try {
            $course->delete();
        } catch (\Exception $e) {
            return back()->withErrors(['noDestroy' => 'Não foi possível excluir o curso: ' . $e->getMessage()]);
        }

        return redirect()->route('courses.index')->with('success', 'Curso excluído com sucesso.');
    }
}
