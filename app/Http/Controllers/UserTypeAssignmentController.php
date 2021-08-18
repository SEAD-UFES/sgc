<?php

namespace App\Http\Controllers;

use App\Models\UserTypeAssignment;
use Illuminate\Http\Request;
use App\CustomClasses\SgcLogger;
use App\Models\UserType;
use App\Models\Course;
use App\Models\User;

class UserTypeAssignmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $userTypeAssignments = UserTypeAssignment::paginate(10);

        return view('userTypeAssignment.index', compact('userTypeAssignments'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::orderBy('email')->get();
        $userTypes = UserType::orderBy('name')->get();
        $courses = Course::orderBy('name')->get();
        $userTypeAssignment = new UserTypeAssignment;

        //write on log
        SgcLogger::writeLog('UserTypeAssignment');

        return view('userTypeAssignment.create', compact('users', 'userTypes', 'courses', 'userTypeAssignment'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //save the model
        $userTypeAssignment = new UserTypeAssignment();
        $userTypeAssignment->user_id = $request->user_id;
        $userTypeAssignment->user_type_id = $request->userType_id;
        $userTypeAssignment->course_id = $request->course_id;
        $userTypeAssignment->begin = $request->begin;
        $userTypeAssignment->end = $request->end;
        $userTypeAssignment->save();

        //write on log
        SgcLogger::writeLog($userTypeAssignment);

        return redirect()->route('userTypeAssignments.index')->with('success', 'Atribuição de Papel criada com sucesso.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\UserTypeAssignment  $userTypeAssignment
     * @return \Illuminate\Http\Response
     */
    public function show(UserTypeAssignment $userTypeAssignment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\UserTypeAssignment  $userTypeAssignment
     * @return \Illuminate\Http\Response
     */
    public function edit(UserTypeAssignment $userTypeAssignment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\UserTypeAssignment  $userTypeAssignment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UserTypeAssignment $userTypeAssignment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\UserTypeAssignment  $userTypeAssignment
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserTypeAssignment $userTypeAssignment)
    {
        //
    }
}
