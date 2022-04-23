<?php

namespace App\Http\Controllers;

use App\Models\Pole;
use App\Models\Role;
use App\Models\State;
use App\Models\Course;
use App\Models\Gender;
use App\Models\Approved;
use App\Models\DocumentType;
use Illuminate\Http\Request;
use App\Models\ApprovedState;
use App\Models\MaritalStatus;
use App\Services\ApprovedService;
use Illuminate\Support\Facades\Gate;
use App\CustomClasses\ModelFilterHelpers;
use App\Http\Requests\ImportApprovedRequest;
use App\Exceptions\EmployeeAlreadyExistsException;
use App\Models\Employee;

class ApprovedController extends Controller
{
    public function __construct(ApprovedService $approvedService)
    {
        $this->service = $approvedService;
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
        if (!Gate::allows('approved-list')) {
            return response()->view('access.denied')->setStatusCode(401);
        }

        //filters
        $filters = ModelFilterHelpers::buildFilters($request, Approved::$accepted_filters);

        //get approved states
        $approvedStates = ApprovedState::all();

        $approveds = $this->service->list();

        return view('approved.index', compact('approveds', 'approvedStates', 'filters'))->with('i', (request()->input('page', 1) - 1) * 10);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() // import spreadsheet file view
    {
        //check access permission
        if (!Gate::allows('approved-store')) {
            return response()->view('access.denied')->setStatusCode(401);
        }

        return view('approved.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Approved  $approved
     * @return \Illuminate\Http\Response
     */
    public function show(Approved $approved)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Approved  $approved
     * @return \Illuminate\Http\Response
     */
    public function edit(Approved $approved)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Approved  $approved
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Approved $approved)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Approved  $approved
     * @return \Illuminate\Http\Response
     */
    public function destroy(Approved $approved)
    {
        //check access permission
        if (!Gate::allows('approved-destroy')) {
            return response()->view('access.denied')->setStatusCode(401);
        }

        try {
            $this->service->delete($approved);
        } catch (\Exception $e) {
            return back()->withErrors(['noDestroy' => 'Não foi possível excluir o Aprovado: ' . $e->getMessage()]);
        }

        return redirect()->route('approveds.index')->with('success', 'Aprovado retirado da lista.');
    }
    
    /**
     * Undocumented function
     *
     * @param Request $request
     * @param Approved $approved
     * @return void
     */
    public function changeState(Request $request, Approved $approved)
    {
        //check access permission
        if (!Gate::allows('approved-update-state')) {
            return response()->view('access.denied')->setStatusCode(401);
        }

        try {
            $this->service->changeState($request->all(), $approved);
        } catch (\Exception $e) {
            return back()->withErrors(['noStore' => 'Não foi possível salvar o Aprovado: ' . $e->getMessage()]);
        }

        return redirect()->route('approveds.index')->with('success', 'Aprovado alterado com sucesso.');
    }
    
    /**
     * Undocumented function
     *
     * @param Request $request
     * @param Approved $approved
     * @return void
     */
    public function designate(Request $request, Approved $approved)
    {
        //check access permission
        if (!Gate::allows('approved-designate')) {
            return response()->view('access.denied')->setStatusCode(401);
        }

        $existantEmployee = Employee::where('email', $approved->email)->first();
        if ($existantEmployee) {
            return redirect()->route('approveds.index')->withErrors(['employeeAlreadyExists' => 'Já existe Colaborador no sistema com o mesmo email do Aprovado.']);
        }

        $genders = Gender::orderBy('name')->get();
        $birthStates = State::orderBy('name')->get();
        $documentTypes = DocumentType::orderBy('name')->get();
        $maritalStatuses = MaritalStatus::orderBy('name')->get();
        $addressStates = State::orderBy('name')->get();

        // Create a temporary object Employee to fill with the approved current data
        $employee = new Employee;
        $employee->name = $approved->name;
        $employee->email = $approved->email;
        $employee->area_code = $approved->area_code;
        $employee->phone = $approved->phone;
        $employee->mobile = $approved->mobile;

        $fromApproved = true;

        return view('approved.designate', compact('genders', 'birthStates', 'documentTypes', 'maritalStatuses', 'addressStates', 'employee', 'fromApproved'));
    }
    
    /**
     * Undocumented function
     *
     * @param ImportApprovedRequest $request
     * @return void
     */
    public function import(ImportApprovedRequest $request)
    {
        //check access permission
        if (!Gate::allows('approved-store')) {
            return response()->view('access.denied')->setStatusCode(401);
        }

        $roles = Role::orderBy('name')->get();
        $courses = Course::orderBy('name')->get();
        $poles = Pole::orderBy('name')->get();

        try {
            $approveds = $this->service->importApproveds($request->file('file'));
        } catch (\Exception $e) {
            return redirect()->route('approveds.index')->withErrors($e->getMessage());
        }

        return view('approved.review', compact('approveds', 'roles', 'courses', 'poles'))->with('success', 'Aprovados importados da planilha.');
    }
    
    /**
     * Undocumented function
     *
     * @param Request $request
     * @return void
     */
    public function massStore(Request $request)
    {
        //check access permission
        if (!Gate::allows('approved-store')) {
            return response()->view('access.denied')->setStatusCode(401);
        }

        try {
            $this->service->massStore($request->all());
        } catch (\Exception $e) {
            return redirect()->route('approveds.index')->withErrors($e->getMessage());
        }

        return redirect()->route('approveds.index')->with('success', 'Aprovados importados com sucesso.');
    }
}
