<?php

namespace App\Http\Controllers;

use App\CustomClasses\ModelFilterHelpers;
use App\CustomClasses\SgcLogger;
use App\Http\Requests\StoreUserTypeAssignmentRequest;
use App\Http\Requests\UpdateUserTypeAssignmentRequest;
use App\Models\Course;
use App\Models\User;
use App\Models\UserType;
use App\Models\UserTypeAssignment;
use App\Services\UserTypeAssignmentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

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
        if (! Gate::allows('userTypeAssignment-list')) {
            SgcLogger::logBadAttemptOnUri($request->getRequestUri(), 403);
            abort(403);
        }

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
    public function create(Request $request)
    {
        //check access permission
        if (! Gate::allows('userTypeAssignment-store')) {
            SgcLogger::logBadAttemptOnUri($request->getRequestUri(), 403);
            abort(403);
        }

        $users = User::orderBy('email')->get();
        $userTypes = UserType::orderBy('name')->get();
        $courses = Course::orderBy('name')->get();

        return view('userTypeAssignment.create', compact('users', 'userTypes', 'courses'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUserTypeAssignmentRequest $request)
    {
        //check access permission
        if (! Gate::allows('userTypeAssignment-store')) {
            SgcLogger::logBadAttemptOnUri($request->getRequestUri(), 403);
            abort(403);
        }

        try {
            $this->service->create($request->validated());
        } catch (\Exception $e) {
            return redirect()->route('userTypeAssignments.index')->withErrors(['noStore' => 'Não foi possível salvar a Atribuição de Papel: ' . $e->getMessage()]);
        }

        return redirect()->route('userTypeAssignments.index')->with('success', 'Atribuição de Papel criada com sucesso.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\UserTypeAssignment  $userTypeAssignment
     *
     * @return \Illuminate\Http\Response
     */
    public function show(UserTypeAssignment $userTypeAssignment, Request $request)
    {
        //check access permission
        if (! Gate::allows('userTypeAssignment-show')) {
            SgcLogger::logBadAttemptOnUri($request->getRequestUri(), 403);
            abort(403);
        }

        $this->service->read($userTypeAssignment);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\UserTypeAssignment  $userTypeAssignment
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(UserTypeAssignment $userTypeAssignment, Request $request)
    {
        //check access permission
        if (! Gate::allows('userTypeAssignment-update')) {
            SgcLogger::logBadAttemptOnUri($request->getRequestUri(), 403);
            abort(403);
        }

        $users = User::orderBy('email')->get();
        $userTypes = UserType::orderBy('name')->get();
        $courses = Course::orderBy('name')->get();

        return view('userTypeAssignment.edit', compact('users', 'userTypes', 'courses', 'userTypeAssignment'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\UserTypeAssignment  $userTypeAssignment
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserTypeAssignmentRequest $request, UserTypeAssignment $userTypeAssignment)
    {
        //check access permission
        if (! Gate::allows('userTypeAssignment-update')) {
            SgcLogger::logBadAttemptOnUri($request->getRequestUri(), 403);
            abort(403);
        }

        try {
            $userTypeAssignment = $this->service->update($request->validated(), $userTypeAssignment);
        } catch (\Exception $e) {
            return back()->withErrors(['noStore' => 'Não foi possível salvar a Atribuição de Papel: ' . $e->getMessage()]);
        }

        return redirect()->route('userTypeAssignments.index')->with('success', 'Atribuição de Papel atualizada com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\UserTypeAssignment  $userTypeAssignment
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserTypeAssignment $userTypeAssignment, Request $request)
    {
        //check access permission
        if (! Gate::allows('userTypeAssignment-destroy')) {
            SgcLogger::logBadAttemptOnUri($request->getRequestUri(), 403);
            abort(403);
        }

        try {
            $this->service->delete($userTypeAssignment);
        } catch (\Exception $e) {
            return back()->withErrors(['noDestroy' => 'Não foi possível excluir a atribuição de papel: ' . $e->getMessage()]);
        }

        return redirect()->route('userTypeAssignments.index')->with('success', 'Atribuição de papel excluído com sucesso.');
    }
}
