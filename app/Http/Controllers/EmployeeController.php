<?php

namespace App\Http\Controllers;

use App\Http\Requests\Employee\CreateEmployeeRequest;
use App\Http\Requests\Employee\DestroyEmployeeRequest;
use App\Http\Requests\Employee\EditEmployeeRequest;
use App\Http\Requests\Employee\IndexEmployeeRequest;
use App\Http\Requests\Employee\ShowEmployeeRequest;
use App\Http\Requests\Employee\StoreEmployeeRequest;
use App\Http\Requests\Employee\UpdateEmployeeRequest;
use App\Models\Employee;
use App\Services\EmployeeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class EmployeeController extends Controller
{
    public function __construct(private EmployeeService $service)
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @param IndexEmployeeRequest $request
     *
     * @return View
     */
    public function index(IndexEmployeeRequest $request): View
    {
        $employees = $this->service->list();

        return view('employee.index', ['employees' => $employees])->with('i', (request()->input('page', 1) - 1) * 10);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param CreateEmployeeRequest $request
     *
     * @return View
     */
    public function create(CreateEmployeeRequest $request): View
    {
        $fromApplicant = false;

        return view('employee.create', ['fromApplicant' => $fromApplicant]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreEmployeeRequest $request
     *
     * @return RedirectResponse
     */
    public function store(StoreEmployeeRequest $request): RedirectResponse
    {
        try {
            $this->service->create($request->toDto());
        } catch (\Exception $exception) {
            return redirect()->route('employees.index')->withErrors(['noStore' => 'Não foi possível salvar o Colaborador: ' . $exception->getMessage()]);
        }

        return redirect()->route('employees.index')->with('success', 'Colaborador criado com sucesso.');
    }

    /**
     * Display the specified resource.
     *
     * @param ShowEmployeeRequest $request
     * @param  Employee  $employee
     *
     * @return View
     */
    public function show(ShowEmployeeRequest $request, Employee $employee): View
    {
        $employee = $this->service->read($employee);

        return view('employee.show', ['employee' => $employee]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param EditEmployeeRequest $request
     * @param  Employee  $employee
     *
     * @return View
     */
    public function edit(EditEmployeeRequest $request, Employee $employee): View
    {
        $fromApplicant = false;

        $employee = $this->service->read($employee);

        return view('employee.edit', ['employee' => $employee, 'fromApplicant' => $fromApplicant]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateEmployeeRequest $request
     * @param Employee $employee
     *
     * @return RedirectResponse
     */
    public function update(UpdateEmployeeRequest $request, Employee $employee): RedirectResponse
    {
        try {
            $this->service->update($request->toDto(), $employee);
        } catch (\Exception $exception) {
            return back()->withErrors(['noStore' => 'Não foi possível salvar o Colaborador: ' . $exception->getMessage()]);
        }

        return redirect()->route('employees.index')->with('success', 'Colaborador atualizado com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DestroyEmployeeRequest $request
     * @param  Employee  $employee
     *
     * @return RedirectResponse
     */
    public function destroy(DestroyEmployeeRequest $request, Employee $employee): RedirectResponse
    {
        try {
            $this->service->delete($employee);
        } catch (\Exception $exception) {
            return redirect()->route('employees.index')->withErrors(['noDestroy' => 'Não foi possível excluir o Colaborador: ' . $exception->getMessage()]);
        }

        return redirect()->route('employees.index')->with('success', 'Colaborador excluído com sucesso.');
    }
}
