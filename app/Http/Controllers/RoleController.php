<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\GrantType;
use Illuminate\Http\Request;
use App\Services\RoleService;
use App\CustomClasses\SgcLogger;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\CustomClasses\ModelFilterHelpers;

class RoleController extends Controller
{
    public function __construct(RoleService $roleService)
    {
        $this->service = $roleService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //check access permission
        if (!Gate::allows('role-list')) return response()->view('access.denied')->setStatusCode(401);

        //filters
        $filters = ModelFilterHelpers::buildFilters($request, Role::$accepted_filters);

        $roles = $this->service->list();

        return view('role.index', compact('roles', 'filters'))->with('i', (request()->input('page', 1) - 1) * 10);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //check access permission
        if (!Gate::allows('role-store')) return response()->view('access.denied')->setStatusCode(401);

        $grantTypes = GrantType::orderBy('name')->get();

        SgcLogger::writeLog(target: 'Role');

        return view('role.create', compact('grantTypes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRoleRequest $request)
    {
        //check access permission
        if (!Gate::allows('role-store')) return response()->view('access.denied')->setStatusCode(401);

        try {
            $this->service->create($request->all());
        } catch (\Exception $e) {
            return redirect()->route('bonds.index')->withErrors(['noStore' => 'Não foi possível salvar a Atribuição: ' . $e->getMessage()]);
        }

        return redirect()->route('roles.index')->with('success', 'Atribuição criada com sucesso.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function show(Role $role)
    {
        //check access permission
        if (!Gate::allows('role-show')) return response()->view('access.denied')->setStatusCode(401);

        SgcLogger::writeLog(target: $role);

        return view('role.show', compact('role'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        //check access permission
        if (!Gate::allows('role-update')) return response()->view('access.denied')->setStatusCode(401);

        $grantTypes = GrantType::orderBy('name')->get();

        SgcLogger::writeLog(target: $role);

        return view('role.edit', compact('role', 'grantTypes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRoleRequest $request, Role $role)
    {
        //check access permission
        if (!Gate::allows('role-update')) return response()->view('access.denied')->setStatusCode(401);

        try {
            $this->service->update($request->all(), $role);
        } catch (\Exception $e) {
            return back()->withErrors(['noStore' => 'Não foi possível salvar a Atribuição: ' . $e->getMessage()]);
        }

        return redirect()->route('roles.index')->with('success', 'Atribuição atualizada com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        //check access permission
        if (!Gate::allows('role-destroy')) return response()->view('access.denied')->setStatusCode(401);

        try {
            $this->service->delete($role);
        } catch (\Exception $e) {
            return back()->withErrors(['noDestroy' => 'Não foi possível excluir a Atribuição: ' . $e->getMessage()]);
        }

        return redirect()->route('roles.index')->with('success', 'Atribuição excluída com sucesso.');
    }
}
