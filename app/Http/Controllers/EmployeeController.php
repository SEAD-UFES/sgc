<?php

namespace App\Http\Controllers;

use App\Helpers\ModelFilterHelper;
use App\Http\Requests\CreateEmployeeRequest;
use App\Http\Requests\DestroyEmployeeRequest;
use App\Http\Requests\EditEmployeeRequest;
use App\Http\Requests\IndexEmployeeRequest;
use App\Http\Requests\ShowEmployeeRequest;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Models\Employee;
use App\Models\EmployeeDocument;
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
        //filters
        $filters = ModelFilterHelper::buildFilters($request, Employee::$accepted_filters);

        $employees = $this->service->list();

        return view('employee.index', compact('employees', 'filters'))->with('i', (request()->input('page', 1) - 1) * 10);
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
        $fromApproved = false;

        return view('employee.create', compact('fromApproved'));
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
            $employee = $this->service->create($request->validated());
        } catch (\Exception $e) {
            return redirect()->route('employees.index')->withErrors(['noStore' => 'Não foi possível salvar o Colaborador: ' . $e->getMessage()]);
        }

        if ($request->importDocuments === 'true') {
            return redirect()->route('employeesDocuments.createMany', $employee->id)->with('success', 'Colaborador criado com sucesso.');
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
        $this->service->read($employee);

        $employeeDocuments = EmployeeDocument::where('employee_id', $employee->id)->with('document')->orderBy('updated_at', 'desc')->get();
        $activeBonds = $employee->bonds()->inActivePeriod()->orderBy('begin', 'ASC')->get();

        return view('employee.show', compact(['employee', 'employeeDocuments', 'activeBonds']));
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
        $fromApproved = false;

        return view('employee.edit', compact('employee', 'fromApproved'));
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
            $this->service->update($request->validated(), $employee);
        } catch (\Exception $e) {
            return back()->withErrors(['noStore' => 'Não foi possível salvar o Colaborador: ' . $e->getMessage()]);
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
        } catch (\Exception $e) {
            return redirect()->route('employees.index')->withErrors(['noDestroy' => 'Não foi possível excluir o Colaborador: ' . $e->getMessage()]);
        }

        return redirect()->route('employees.index')->with('success', 'Colaborador excluído com sucesso.');
    }
}
