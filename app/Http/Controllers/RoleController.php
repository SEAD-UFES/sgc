<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use App\CustomClasses\SgcLogger;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Models\GrantType;
use App\CustomClasses\ModelFilterHelpers;
use Illuminate\Support\Facades\Gate;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //check access permission
        if (!Gate::allows('role-list')) return response()->view('access.denied')->setStatusCode(401);

        $roles = Role::sortable(['name' => 'asc'])->paginate(10);

        $roles_query = new Role();

        //filters
        $filters = ModelFilterHelpers::buildFilters($request, Role::$accepted_filters);
        $roles_query = $roles_query->AcceptRequest(Role::$accepted_filters)->filter();

        //sort
        $roles_query = $roles_query->sortable(['name' => 'asc']);

        //get paginate and add querystring on paginate links
        $roles = $roles_query->paginate(10);
        $roles->appends($request->all());

        //write on log
        SgcLogger::writeLog(target: 'Role');

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
        $role = new Role;

        SgcLogger::writeLog(target: 'Role');

        return view('role.create', compact('role', 'grantTypes'));
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

        $role = new Role;

        $role->name = $request->name;
        $role->description = $request->description;
        $role->grant_value = $request->grantValue;
        $role->grant_type_id = $request->grantTypes;

        SgcLogger::writeLog(target: $role);

        $role->save();

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

        $role->name = $request->name;
        $role->description = $request->description;
        $role->grant_value = $request->grantValue;
        $role->grant_type_id = $request->grantTypes;

        try {
            $role->save();
        } catch (\Exception $e) {
            return back()->withErrors(['noStore' => 'Não foi possível salvar a Atribuição: ' . $e->getMessage()]);
        }

        SgcLogger::writeLog(target: $role);

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

        SgcLogger::writeLog(target: $role);

        try {
            $role->delete();
        } catch (\Exception $e) {
            return back()->withErrors(['noDestroy' => 'Não foi possível salvar a Atribuição: ' . $e->getMessage()]);
        }

        return redirect()->route('roles.index')->with('success', 'Atribuição excluída com sucesso.');
    }
}
