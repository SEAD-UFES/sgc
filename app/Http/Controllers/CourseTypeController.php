<?php

namespace App\Http\Controllers;

use App\Models\CourseType;
use Illuminate\Http\Request;
use App\CustomClasses\SgcLogger;

class CourseTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $coursetypes = CourseType::all();

        SgcLogger::writeLog('CourseType');

        return view('coursetype.index', compact('coursetypes'));
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
