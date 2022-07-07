<?php

namespace App\Http\Controllers;

use App\Helpers\ModelFilterHelper;
use App\Models\CourseType;
use App\Services\CourseTypeService;
use Illuminate\Http\Request;

class CourseTypeController extends Controller
{
    public function __construct(CourseTypeService $courseTypeService)
    {
        $this->service = $courseTypeService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //filters
        $filters = ModelFilterHelper::buildFilters($request, CourseType::$accepted_filters);

        $courseTypes = $this->service->list();

        return view('coursetype.index', compact('courseTypes', 'filters'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CourseType  $courseType
     *
     * @return \Illuminate\Http\Response
     */
    public function show(CourseType $courseType)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CourseType  $courseType
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(CourseType $courseType)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CourseType  $courseType
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CourseType $courseType)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CourseType  $courseType
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(CourseType $courseType)
    {
    }
}
