<?php

namespace App\Http\Controllers;

use App\Helpers\ModelFilterHelper;
use App\Http\Requests\Role\CreateRoleRequest;
use App\Http\Requests\Role\DestroyRoleRequest;
use App\Http\Requests\Role\EditRoleRequest;
use App\Http\Requests\Role\IndexRoleRequest;
use App\Http\Requests\Role\ShowRoleRequest;
use App\Http\Requests\Role\StoreRoleRequest;
use App\Http\Requests\Role\UpdateRoleRequest;
use App\Models\Role;
use App\Services\RoleService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class RoleController extends Controller
{
    public function __construct(private RoleService $service)
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @param IndexRoleRequest $request
     *
     * @return View
     */
    public function index(IndexRoleRequest $request): View
    {
        //filters
        $filters = ModelFilterHelper::buildFilters($request, Role::$accepted_filters);

        $roles = $this->service->list();

        return view('role.index', compact('roles', 'filters'))->with('i', (request()->input('page', 1) - 1) * 10);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param CreateRoleRequest $request
     *
     * @return View
     */
    public function create(CreateRoleRequest $request): View
    {
        return view('role.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreRoleRequest  $request
     *
     * @return RedirectResponse
     */
    public function store(StoreRoleRequest $request): RedirectResponse
    {
        try {
            $this->service->create($request->toDto());
        } catch (\Exception $e) {
            return redirect()->route('bonds.index')->withErrors(['noStore' => 'Não foi possível salvar a Função: ' . $e->getMessage()]);
        }

        return redirect()->route('roles.index')->with('success', 'Função criada com sucesso.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param EditRoleRequest $request
     * @param  Role  $role
     *
     * @return View
     */
    public function edit(EditRoleRequest $request, Role $role): View
    {
        return view('role.edit', compact('role'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateRoleRequest  $request
     * @param  Role  $role
     *
     * @return RedirectResponse
     */
    public function update(UpdateRoleRequest $request, Role $role): RedirectResponse
    {
        try {
            $this->service->update($request->toDto(), $role);
        } catch (\Exception $e) {
            return back()->withErrors(['noStore' => 'Não foi possível salvar a Função: ' . $e->getMessage()]);
        }

        return redirect()->route('roles.index')->with('success', 'Função atualizada com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  DestroyRoleRequest $request
     * @param  Role  $role
     *
     * @return RedirectResponse
     */
    public function destroy(DestroyRoleRequest $request, Role $role): RedirectResponse
    {
        try {
            $this->service->delete($role);
        } catch (\Exception $e) {
            return back()->withErrors(['noDestroy' => 'Não foi possível excluir a Função: ' . $e->getMessage()]);
        }

        return redirect()->route('roles.index')->with('success', 'Função excluída com sucesso.');
    }
}
