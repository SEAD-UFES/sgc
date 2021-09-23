<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Course;
use App\Models\UserType;
use Illuminate\Http\Request;
use App\CustomClasses\SgcLogger;
use App\Models\UserTypeAssignment;
use Illuminate\Support\Facades\Gate;
use App\CustomClasses\ModelFilterHelpers;
use App\Services\UserTypeAssignmentService;
use App\Http\Requests\StoreUserTypeAssignmentRequest;
use App\Http\Requests\UpdateUserTypeAssignmentRequest;

class UserTypeAssignmentController extends Controller
{
    public function __construct(UserTypeAssignmentService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //check access permission
        if (!Gate::allows('userTypeAssignment-list')) return response()->view('access.denied')->setStatusCode(401);

        //filters
        $filters = ModelFilterHelpers::buildFilters($request, UserTypeAssignment::$accepted_filters);
        
        $userTypeAssignments = $this->service->list();

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
        
        SgcLogger::writeLog(target: 'UserTypeAssignment');

        return view('userTypeAssignment.create', compact('users', 'userTypes', 'courses'));
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

        try {
            $this->service->create($request->all());
        } catch (\Exception $e) {
            return redirect()->route('userTypeAssignments.index')->withErrors(['noStore' => 'Não foi possível salvar a Atribuição de Papel: ' . $e->getMessage()]);
        }

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

        try {
            $userTypeAssignment = $this->service->update($request->all(), $userTypeAssignment);
        } catch (\Exception $e) {
            return back()->withErrors(['noStore' => 'Não foi possível salvar a Atribuição de Papel: ' . $e->getMessage()]);
        }

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

        try {
            $this->service->delete($userTypeAssignment);
        } catch (\Exception $e) {
            return back()->withErrors(['noDestroy' => 'Não foi possível excluir a atribuição de papel: ' . $e->getMessage()]);
        }

        return redirect()->route('userTypeAssignments.index')->with('success', 'Atribuição de papel excluído com sucesso.');
    }
}
