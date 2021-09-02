<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use App\CustomClasses\SgcLogger;
use App\Models\Gender;
use App\Models\DocumentType;
use App\Models\MaritalStatus;
use App\Models\State;
use App\Models\User;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\CustomClasses\ModelFilterHelpers;
use Illuminate\Support\Facades\Gate;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //check access permission
        if (!Gate::allows('employee-list')) return response()->view('access.denied')->setStatusCode(401);

        $employees_query = Employee::with(['gender', 'birthState', 'documentType', 'maritalStatus', 'addressState', 'user']);

        //filters
        $filters = ModelFilterHelpers::buildFilters($request, Employee::$accepted_filters);
        $employees_query = $employees_query->AcceptRequest(Employee::$accepted_filters)->filter();

        //sort
        $employees_query = $employees_query->sortable(['updated_at' => 'desc']);

        //get paginate and add querystring on paginate links
        $employees = $employees_query->paginate(10);
        $employees->appends($request->all());

        //write on log
        SgcLogger::writeLog('Employee');

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

        $employee = new Employee;

        SgcLogger::writeLog('Employee');

        return view('employee.create', compact('genders', 'birthStates', 'documentTypes', 'maritalStatuses', 'addressStates', 'employee'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreEmployeeRequest $request)
    {
        //check access permission
        if (!Gate::allows('employee-store')) return response()->view('access.denied')->setStatusCode(401);

        $employee = new Employee;

        $employee->name = $request->name;
        $employee->cpf = $request->cpf;
        $employee->job = $request->job;
        $employee->gender_id = $request->genders;
        $employee->birthday = $request->birthday;
        $employee->birth_state_id = $request->birthStates;
        $employee->birth_city = $request->birthCity;
        $employee->id_number = $request->idNumber;
        $employee->document_type_id = $request->documentTypes;
        $employee->id_issue_date = $request->idIssueDate;
        $employee->id_issue_agency = $request->idIssueAgency;
        $employee->marital_status_id = $request->maritalStatuses;
        $employee->spouse_name = $request->spouseName;
        $employee->father_name = $request->fatherName;
        $employee->mother_name = $request->motherName;
        $employee->address_street = $request->addressStreet;
        $employee->address_complement = $request->addressComplement;
        $employee->address_number = $request->addressNumber;
        $employee->address_district = $request->addressDistrict;
        $employee->address_postal_code = $request->addressPostalCode;
        $employee->address_state_id = $request->addressStates;
        $employee->address_city = $request->addressCity;
        $employee->area_code = $request->areaCode;
        $employee->phone = $request->phone;
        $employee->mobile = $request->mobile;
        $employee->email = $request->email;

        try {
            $employee->save();
        } catch (\Exception $e) {
            return redirect()->route('employees.index')->withErrors(['noStore' => 'Não foi possível salvar o Colaborador: ' . $e->getMessage()]);
        }

        $existentUser = User::where('email', $request->email)->first();

        if (!is_null($existentUser)) {
            $existentUser->employee_id = $employee->id;
            try {
                $existentUser->save();
            } catch (\Exception $e) {
                return redirect()->route('employees.index')->withErrors(['noStore' => 'Não foi possível salvar o Colaborador: ' . $e->getMessage()]);
            }

            SgcLogger::writeLog($existentUser, 'Updated existent Employee info on User');
        }

        if ($request->importDocuments == 'true')
            return redirect()->route('employees.document.create.id', $employee->id)->with('success', 'Colaborador criado com sucesso.');
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

        $genders = Gender::orderBy('name')->get();
        $birthStates = State::orderBy('name')->get();
        $documentTypes = DocumentType::orderBy('name')->get();
        $maritalStatuses = MaritalStatus::orderBy('name')->get();
        $addressStates = State::orderBy('name')->get();

        SgcLogger::writeLog($employee);

        return view('employee.edit', compact('genders', 'birthStates', 'documentTypes', 'maritalStatuses', 'addressStates', 'employee'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateEmployeeRequest $request, Employee $employee)
    {
        //check access permission
        if (!Gate::allows('employee-update')) return response()->view('access.denied')->setStatusCode(401);

        $currentUser = $employee->user;

        $employee->name = $request->name;
        $employee->cpf = $request->cpf;
        $employee->job = $request->job;
        $employee->gender_id = $request->genders;
        $employee->birthday = $request->birthday;
        $employee->birth_state_id = $request->birthStates;
        $employee->birth_city = $request->birthCity;
        $employee->id_number = $request->idNumber;
        $employee->document_type_id = $request->documentTypes;
        $employee->id_issue_date = $request->idIssueDate;
        $employee->id_issue_agency = $request->idIssueAgency;
        $employee->marital_status_id = $request->maritalStatuses;
        $employee->spouse_name = $request->spouseName;
        $employee->father_name = $request->fatherName;
        $employee->mother_name = $request->motherName;
        $employee->address_street = $request->addressStreet;
        $employee->address_complement = $request->addressComplement;
        $employee->address_number = $request->addressNumber;
        $employee->address_district = $request->addressDistrict;
        $employee->address_postal_code = $request->addressPostalCode;
        $employee->address_state_id = $request->addressStates;
        $employee->address_city = $request->addressCity;
        $employee->area_code = $request->areaCode;
        $employee->phone = $request->phone;
        $employee->mobile = $request->mobile;
        $employee->email = $request->email;

        try {
            $employee->save();
        } catch (\Exception $e) {
            return back()->withErrors(['noStore' => 'Não foi possível salvar o Colaborador: ' . $e->getMessage()]);
        }

        $existentUser = User::where('email', $request->email)->first();

        if (!is_null($existentUser)) {
            $currentUser->employee_id = null;
            try {
                $currentUser->save();
            } catch (\Exception $e) {
                return back()->withErrors(['noStore' => 'Não foi possível salvar o Usuário: ' . $e->getMessage()]);
            }

            $existentUser->employee_id = $employee->id;
            try {
                $existentUser->save();
            } catch (\Exception $e) {
                return back()->withErrors(['noStore' => 'Não foi possível salvar o Usuário: ' . $e->getMessage()]);
            }

            SgcLogger::writeLog($existentUser, 'Updated existent Employee info on User');
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

        $currentUser = $employee->user;

        if (!is_null($currentUser)) {
            $currentUser->employee_id = null;
            try {
                $currentUser->save();
            } catch (\Exception $e) {
                return back()->withErrors(['noStore' => 'Não foi possível salvar o Usuário: ' . $e->getMessage()]);
            }
        }

        try {
            foreach ($employee->courses as $course) $course->bond->bondDocuments()->delete();

            $employee->courses()->detach();
            $employee->employeeDocuments()->delete();
            $employee->delete();
        } catch (\Exception $e) {
            return redirect()->route('employees.index')->withErrors(['noDestroy' => 'Não foi possível excluir o Colaborador: ' . $e->getMessage()]);
        }

        SgcLogger::writeLog($employee);

        return redirect()->route('employees.index')->with('success', 'Colaborador excluído com sucesso.');
    }
}
