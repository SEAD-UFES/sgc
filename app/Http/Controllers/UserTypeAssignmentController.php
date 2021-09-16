<?php

namespace App\Http\Controllers;

use App\Models\UserTypeAssignment;
use Illuminate\Http\Request;
use App\CustomClasses\SgcLogger;
use App\Models\UserType;
use App\Models\Course;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use App\CustomClasses\ModelFilterHelpers;
use App\Http\Requests\StoreUserTypeAssignmentRequest;
use App\Http\Requests\UpdateUserTypeAssignmentRequest;

class UserTypeAssignmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //check access permission
        if (!Gate::allows('userTypeAssignment-list')) return response()->view('access.denied')->setStatusCode(401);

        $userTypeAssignments_query = new UserTypeAssignment();

        //filters
        $filters = ModelFilterHelpers::buildFilters($request, UserTypeAssignment::$accepted_filters);
        $userTypeAssignments_query = $userTypeAssignments_query->AcceptRequest(UserTypeAssignment::$accepted_filters)->filter();

        //sort
        $userTypeAssignments_query = $userTypeAssignments_query->sortable(['updated_at' => 'desc']);

        //get paginate and add querystring on paginate links
        $userTypeAssignments = $userTypeAssignments_query->paginate(10);
        $userTypeAssignments->withQueryString();

        SgcLogger::writeLog(target: 'userTypeAssignment');

        return view('userTypeAssignment.index', compact('userTypeAssignments', 'filters'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //check access permission
        if (!Gate::allows('userTypeAssignment-store')) return response()->view('access.denied')->setStatusCode(401);

        $users = User::orderBy('email')->get();
        $userTypes = UserType::orderBy('name')->get();
        $courses = Course::orderBy('name')->get();
        $userTypeAssignment = new UserTypeAssignment;

        //write on log
        SgcLogger::writeLog(target: 'UserTypeAssignment');

        return view('userTypeAssignment.create', compact('users', 'userTypes', 'courses', 'userTypeAssignment'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUserTypeAssignmentRequest $request)
    {
        //check access permission
        if (!Gate::allows('userTypeAssignment-store')) return response()->view('access.denied')->setStatusCode(401);

        //save the model
        $userTypeAssignment = new UserTypeAssignment();
        $userTypeAssignment->user_id = $request->user_id;
        $userTypeAssignment->user_type_id = $request->userType_id;
        $userTypeAssignment->course_id = $request->course_id;
        $userTypeAssignment->begin = $request->begin;
        $userTypeAssignment->end = $request->end;
        $userTypeAssignment->save();

        //write on log
        SgcLogger::writeLog(target: $userTypeAssignment);

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
        //check access permission
        if (!Gate::allows('userTypeAssignment-show')) return response()->view('access.denied')->setStatusCode(401);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\UserTypeAssignment  $userTypeAssignment
     * @return \Illuminate\Http\Response
     */
    public function edit(UserTypeAssignment $userTypeAssignment)
    {
        //check access permission
        if (!Gate::allows('userTypeAssignment-update')) return response()->view('access.denied')->setStatusCode(401);

        $users = User::orderBy('email')->get();
        $userTypes = UserType::orderBy('name')->get();
        $courses = Course::orderBy('name')->get();

        //write on log
        SgcLogger::writeLog(target: $userTypeAssignment);

        return view('userTypeAssignment.edit', compact('users', 'userTypes', 'courses', 'userTypeAssignment'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\UserTypeAssignment  $userTypeAssignment
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserTypeAssignmentRequest $request, UserTypeAssignment $userTypeAssignment)
    {
        //check access permission
        if (!Gate::allows('userTypeAssignment-update')) return response()->view('access.denied')->setStatusCode(401);

        //save the model
        $userTypeAssignment->user_id = $request->user_id;
        $userTypeAssignment->user_type_id = $request->userType_id;
        $userTypeAssignment->course_id = $request->course_id;
        $userTypeAssignment->begin = $request->begin;
        $userTypeAssignment->end = $request->end;
        
        //write on log
        SgcLogger::writeLog(target: $userTypeAssignment);

        $userTypeAssignment->save();

        return redirect()->route('userTypeAssignments.index')->with('success', 'Atribuição de Papel atualizada com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\UserTypeAssignment  $userTypeAssignment
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserTypeAssignment $userTypeAssignment)
    {
        //check access permission
        if (!Gate::allows('userTypeAssignment-destroy')) return response()->view('access.denied')->setStatusCode(401);

        SgcLogger::writeLog(target: $userTypeAssignment);

        try {
            $userTypeAssignment->delete();
        } catch (\Exception $e) {
            return back()->withErrors(['noDestroy' => 'Não foi possível excluir a atribuição de papel: ' . $e->getMessage()]);
        }

        return redirect()->route('userTypeAssignments.index')->with('success', 'Atribuição de papel excluído com sucesso.');
    }
}
