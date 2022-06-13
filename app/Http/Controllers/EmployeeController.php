<?php

namespace App\Http\Controllers;

use App\CustomClasses\ModelFilterHelpers;
use App\CustomClasses\SgcLogger;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Models\DocumentType;
use App\Models\Employee;
use App\Models\EmployeeDocument;
use App\Models\Gender;
use App\Models\MaritalStatus;
use App\Models\State;
use App\Services\EmployeeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

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
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //check access permission
        if (! Gate::allows('employee-list')) {
            SgcLogger::logBadAttemptOnUri($request, 403);
            abort(403);
        }

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
    public function create(Request $request)
    {
        //check access permission
        if (! Gate::allows('employee-store')) {
            SgcLogger::logBadAttemptOnUri($request, 403);
            abort(403);
        }

        $genders = Gender::orderBy('name')->get();
        $birthStates = State::orderBy('name')->get();
        $documentTypes = DocumentType::orderBy('name')->get();
        $maritalStatuses = MaritalStatus::orderBy('name')->get();
        $addressStates = State::orderBy('name')->get();

        $fromApproved = false;

        return view('employee.create', compact('genders', 'birthStates', 'documentTypes', 'maritalStatuses', 'addressStates', 'fromApproved'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreEmployeeRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(StoreEmployeeRequest $request)
    {
        //check access permission
        if (! Gate::allows('employee-store')) {
            SgcLogger::logBadAttemptOnUri($request, 403);
            abort(403);
        }

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
     * @param  \App\Models\Employee  $employee
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Employee $employee, Request $request)
    {
        //check access permission
        if (! Gate::allows('employee-show')) {
            SgcLogger::logBadAttemptOnUri($request, 403);
            abort(403);
        }

        $this->service->read($employee);

        $employeeDocuments = EmployeeDocument::where('employee_id', $employee->id)->with('document')->orderBy('updated_at', 'desc')->get();
        $activeBonds = $employee->bonds()->inActivePeriod()->orderBy('begin', 'ASC')->get();

        return view('employee.show', compact(['employee', 'employeeDocuments', 'activeBonds']));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Employee  $employee
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Employee $employee, Request $request)
    {
        //check access permission
        if (! Gate::allows('employee-update')) {
            SgcLogger::logBadAttemptOnUri($request, 403);
            abort(403);
        }

        $genders = Gender::orderBy('name')->get();
        $birthStates = State::orderBy('name')->get();
        $documentTypes = DocumentType::orderBy('name')->get();
        $maritalStatuses = MaritalStatus::orderBy('name')->get();
        $addressStates = State::orderBy('name')->get();

        $fromApproved = false;

        return view('employee.edit', compact('genders', 'birthStates', 'documentTypes', 'maritalStatuses', 'addressStates', 'employee', 'fromApproved'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateEmployeeRequest $request
     * @param Employee $employee
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateEmployeeRequest $request, Employee $employee)
    {
        //check access permission
        if (! Gate::allows('employee-update')) {
            SgcLogger::logBadAttemptOnUri($request, 403);
            abort(403);
        }

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
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Employee $employee, Request $request)
    {
        //check access permission
        if (! Gate::allows('employee-destroy')) {
            SgcLogger::logBadAttemptOnUri($request, 403);
            abort(403);
        }

        try {
            $this->service->delete($employee);
        } catch (\Exception $e) {
            return redirect()->route('employees.index')->withErrors(['noDestroy' => 'Não foi possível excluir o Colaborador: ' . $e->getMessage()]);
        }

        return redirect()->route('employees.index')->with('success', 'Colaborador excluído com sucesso.');
    }
}
