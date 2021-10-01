<?php

namespace App\Http\Controllers;

use App\Models\State;
use App\Models\Gender;
use App\Models\Employee;
use App\Models\DocumentType;
use Illuminate\Http\Request;
use App\Models\MaritalStatus;
use App\CustomClasses\SgcLogger;
use App\Services\EmployeeService;
use Illuminate\Support\Facades\Gate;
use App\CustomClasses\ModelFilterHelpers;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;

class EmployeeController extends Controller
{
    public function __construct(EmployeeService $employeeService)
    {
        $this->service = $employeeService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //check access permission
        if (!Gate::allows('employee-list')) return response()->view('access.denied')->setStatusCode(401);

        //filters
        $filters = ModelFilterHelpers::buildFilters($request, Employee::$accepted_filters);

        $employees = $this->service->list();

        return view('employee.index', compact('employees', 'filters'))->with('i', (request()->input('page', 1) - 1) * 10);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //check access permission
        if (!Gate::allows('employee-store')) return response()->view('access.denied')->setStatusCode(401);

        $genders = Gender::orderBy('name')->get();
        $birthStates = State::orderBy('name')->get();
        $documentTypes = DocumentType::orderBy('name')->get();
        $maritalStatuses = MaritalStatus::orderBy('name')->get();
        $addressStates = State::orderBy('name')->get();

        SgcLogger::writeLog(target: 'Employee', action: 'create');

        return view('employee.create', compact('genders', 'birthStates', 'documentTypes', 'maritalStatuses', 'addressStates'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreEmployeeRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreEmployeeRequest $request)
    {
        //check access permission
        if (!Gate::allows('employee-store')) return response()->view('access.denied')->setStatusCode(401);

        try {
            $employee = $this->service->create($request->validated());
        } catch (\Exception $e) {
            return redirect()->route('employees.index')->withErrors(['noStore' => 'Não foi possível salvar o Colaborador: ' . $e->getMessage()]);
        }

        if ($request->importDocuments == 'true')
            return redirect()->route('employeesDocuments.createMany', $employee->id)->with('success', 'Colaborador criado com sucesso.');
        else
            return redirect()->route('employees.index')->with('success', 'Colaborador criado com sucesso.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function show(Employee $employee)
    {
        //check access permission
        if (!Gate::allows('employee-show')) return response()->view('access.denied')->setStatusCode(401);

        SgcLogger::writeLog(target: $employee);

        return view('employee.show', compact('employee'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function edit(Employee $employee)
    {
        //check access permission
        if (!Gate::allows('employee-update')) return response()->view('access.denied')->setStatusCode(401);

        SgcLogger::writeLog(target: $employee);

        $genders = Gender::orderBy('name')->get();
        $birthStates = State::orderBy('name')->get();
        $documentTypes = DocumentType::orderBy('name')->get();
        $maritalStatuses = MaritalStatus::orderBy('name')->get();
        $addressStates = State::orderBy('name')->get();

        return view('employee.edit', compact('genders', 'birthStates', 'documentTypes', 'maritalStatuses', 'addressStates', 'employee'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateEmployeeRequest $request
     * @param Employee $employee
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateEmployeeRequest $request, Employee $employee)
    {
        //check access permission
        if (!Gate::allows('employee-update')) return response()->view('access.denied')->setStatusCode(401);

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
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function destroy(Employee $employee)
    {
        //check access permission
        if (!Gate::allows('employee-destroy')) return response()->view('access.denied')->setStatusCode(401);

        try {
            $this->service->delete($employee);
        } catch (\Exception $e) {
            return redirect()->route('employees.index')->withErrors(['noDestroy' => 'Não foi possível excluir o Colaborador: ' . $e->getMessage()]);
        }

        return redirect()->route('employees.index')->with('success', 'Colaborador excluído com sucesso.');
    }
}
