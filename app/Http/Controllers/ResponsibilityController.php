<?php

namespace App\Http\Controllers;

use App\Helpers\ModelFilterHelper;
use App\Http\Requests\Responsibility\StoreResponsibilityRequest;
use App\Http\Requests\Responsibility\UpdateResponsibilityRequest;
use App\Models\Course;
use App\Models\User;
use App\Models\UserType;
use App\Models\Responsibility;
use App\Services\ResponsibilityService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class ResponsibilityController extends Controller
{
    private ResponsibilityService $service;

    public function __construct(ResponsibilityService $service)
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
        if (! Gate::allows('responsibility-list')) {
            abort(403);
        }

        //filters
        $filters = ModelFilterHelper::buildFilters($request, Responsibility::$accepted_filters);

        $responsibilities = $this->service->list();

        return view('responsibility.index', compact('responsibilities', 'filters'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create(Request $request): View
    {
        //check access permission
        if (! Gate::allows('responsibility-store')) {
            abort(403);
        }

        $users = User::orderBy('email')->get();
        $userTypes = UserType::orderBy('name')->get();
        $courses = Course::orderBy('name')->get();

        return view('responsibility.create', compact('users', 'userTypes', 'courses'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreResponsibilityRequest  $request
     *
     * @return RedirectResponse
     */
    public function store(StoreResponsibilityRequest $request): RedirectResponse
    {
        //check access permission
        if (! Gate::allows('responsibility-store')) {
            abort(403);
        }

        try {
            $this->service->create($request->validated());
        } catch (\Exception $e) {
            return redirect()->route('responsibility.index')->withErrors(['noStore' => 'Não foi possível salvar a Atribuição de Papel: ' . $e->getMessage()]);
        }

        return redirect()->route('responsibility.index')->with('success', 'Atribuição de Papel criada com sucesso.');
    }

    /**
     * Display the specified resource.
     *
     * @param  Responsibility  $responsibility
     *
     * @return View
     */
    public function show(Responsibility $responsibility, Request $request): View
    {
        //check access permission
        if (! Gate::allows('responsibility-show')) {
            abort(403);
        }

        $responsibility = $this->service->read($responsibility);

        return view('responsibility.show', compact('responsibility'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Responsibility  $responsibility
     *
     * @return View
     */
    public function edit(Responsibility $responsibility, Request $request): View
    {
        //check access permission
        if (! Gate::allows('responsibility-update')) {
            abort(403);
        }

        $users = User::orderBy('email')->get();
        $userTypes = UserType::orderBy('name')->get();
        $courses = Course::orderBy('name')->get();

        return view('responsibility.edit', compact('users', 'userTypes', 'courses', 'responsibility'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateResponsibilityRequest  $request
     * @param  Responsibility  $responsibility
     *
     * @return RedirectResponse
     */
    public function update(UpdateResponsibilityRequest $request, Responsibility $responsibility): RedirectResponse
    {
        //check access permission
        if (! Gate::allows('responsibility-update')) {
            abort(403);
        }

        try {
            $responsibility = $this->service->update($request->validated(), $responsibility);
        } catch (\Exception $e) {
            return back()->withErrors(['noStore' => 'Não foi possível salvar a Atribuição de Papel: ' . $e->getMessage()]);
        }

        return redirect()->route('responsibility.index')->with('success', 'Atribuição de Papel atualizada com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Responsibility  $responsibility
     *
     * @return RedirectResponse
     */
    public function destroy(Responsibility $responsibility, Request $request): RedirectResponse
    {
        //check access permission
        if (! Gate::allows('responsibility-destroy')) {
            abort(403);
        }

        try {
            $this->service->delete($responsibility);
        } catch (\Exception $e) {
            return back()->withErrors(['noDestroy' => 'Não foi possível excluir a atribuição de papel: ' . $e->getMessage()]);
        }

        return redirect()->route('responsibility.index')->with('success', 'Atribuição de papel excluído com sucesso.');
    }
}
