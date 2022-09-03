<?php

namespace App\Http\Controllers;

use App\Helpers\ModelFilterHelper;
use App\Http\Requests\StoreUserTypeAssignmentRequest;
use App\Http\Requests\UpdateUserTypeAssignmentRequest;
use App\Models\Course;
use App\Models\User;
use App\Models\UserType;
use App\Models\UserTypeAssignment;
use App\Services\UserTypeAssignmentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class UserTypeAssignmentController extends Controller
{
    private UserTypeAssignmentService $service;

    public function __construct(UserTypeAssignmentService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(Request $request): View
    {
        //check access permission
        if (! Gate::allows('userTypeAssignment-list')) {
            abort(403);
        }

        //filters
        $filters = ModelFilterHelper::buildFilters($request, UserTypeAssignment::$accepted_filters);

        $userTypeAssignments = $this->service->list();

        return view('userTypeAssignment.index', compact('userTypeAssignments', 'filters'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create(Request $request): View
    {
        //check access permission
        if (! Gate::allows('userTypeAssignment-store')) {
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
     * @param  StoreUserTypeAssignmentRequest  $request
     *
     * @return RedirectResponse
     */
    public function store(StoreUserTypeAssignmentRequest $request): RedirectResponse
    {
        //check access permission
        if (! Gate::allows('userTypeAssignment-store')) {
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
     * @param  UserTypeAssignment  $userTypeAssignment
     *
     * @return View
     */
    public function show(UserTypeAssignment $userTypeAssignment, Request $request): View
    {
        //check access permission
        if (! Gate::allows('userTypeAssignment-show')) {
            abort(403);
        }

        $userTypeAssignment = $this->service->read($userTypeAssignment);

        return view('userTypeAssignment.show', compact('userTypeAssignment'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  UserTypeAssignment  $userTypeAssignment
     *
     * @return View
     */
    public function edit(UserTypeAssignment $userTypeAssignment, Request $request): View
    {
        //check access permission
        if (! Gate::allows('userTypeAssignment-update')) {
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
     * @param  UpdateUserTypeAssignmentRequest  $request
     * @param  UserTypeAssignment  $userTypeAssignment
     *
     * @return RedirectResponse
     */
    public function update(UpdateUserTypeAssignmentRequest $request, UserTypeAssignment $userTypeAssignment): RedirectResponse
    {
        //check access permission
        if (! Gate::allows('userTypeAssignment-update')) {
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
     * @param  UserTypeAssignment  $userTypeAssignment
     *
     * @return RedirectResponse
     */
    public function destroy(UserTypeAssignment $userTypeAssignment, Request $request): RedirectResponse
    {
        //check access permission
        if (! Gate::allows('userTypeAssignment-destroy')) {
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
