<?php

namespace App\Http\Controllers;

use App\Models\CourseType;
use Illuminate\Http\Request;
use App\CustomClasses\SgcLogger;
use App\CustomClasses\ModelFilterHelpers;

class CourseTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $coursesTypes_query = new CourseType();

        //filters
        $filters = ModelFilterHelpers::buildFilters($request, CourseType::$accepted_filters);
        $coursesTypes_query = $coursesTypes_query->AcceptRequest(CourseType::$accepted_filters)->filter();

        //sort
        $coursesTypes_query = $coursesTypes_query->sortable(['name' => 'asc']);

        //get paginate and add querystring on paginate links
        $courseTypes = $coursesTypes_query->paginate(10);
        $courseTypes->appends($request->all());

        //write on log
        SgcLogger::writeLog(target: 'CourseType');

        return view('coursetype.index', compact('courseTypes', 'filters'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CourseType  $courseType
     * @return \Illuminate\Http\Response
     */
    public function show(CourseType $courseType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CourseType  $courseType
     * @return \Illuminate\Http\Response
     */
    public function edit(CourseType $courseType)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CourseType  $courseType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CourseType $courseType)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CourseType  $courseType
     * @return \Illuminate\Http\Response
     */
    public function destroy(CourseType $courseType)
    {
        //
    }
}
