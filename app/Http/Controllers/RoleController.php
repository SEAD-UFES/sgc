<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use App\CustomClasses\SgcLogger;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Models\GrantType;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $roles = Role::sortable(['name' => 'asc'])->paginate(10);

        //add query string on page links
        $roles->appends($request->all());

        SgcLogger::writeLog('Role');

        return view('role.index', compact('roles'))->with('i', (request()->input('page', 1) - 1) * 10);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $grantTypes = GrantType::orderBy('name')->get();
        $role = new Role;

        SgcLogger::writeLog('Role');

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
        $role = new Role;

        $role->name = $request->name;
        $role->description = $request->description;
        $role->grant_value = $request->grantValue;
        $role->grant_type_id = $request->grantTypes;

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
        $grantTypes = GrantType::orderBy('name')->get();

        SgcLogger::writeLog($role);

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
        $role->name = $request->name;
        $role->description = $request->description;
        $role->grant_value = $request->grantValue;
        $role->grant_type_id = $request->grantTypes;

        try {
            $role->save();
        } catch (\Exception $e) {
            return back()->withErrors(['noStore' => 'Não foi possível salvar a Atribuição: ' . $e->getMessage()]);
        }

        SgcLogger::writeLog($role);

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
        SgcLogger::writeLog($role);

        try {
            $role->delete();
        } catch (\Exception $e) {
            return back()->withErrors(['noDestroy' => 'Não foi possível salvar a Atribuição: ' . $e->getMessage()]);
        }

        return redirect()->route('roles.index')->with('success', 'Atribuição excluída com sucesso.');
    }
}
