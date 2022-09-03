<?php

namespace App\Http\Controllers;

use App\Events\EmployeeDesignated;
use App\Helpers\ModelFilterHelper;
use App\Http\Requests\ImportApprovedsFileRequest;
use App\Http\Requests\StoreApprovedRequest;
use App\Http\Requests\StoreApprovedsRequest;
use App\Models\Approved;
use App\Models\ApprovedState;
use App\Models\Course;
use App\Models\DocumentType;
use App\Models\Employee;
use App\Models\Gender;
use App\Models\MaritalStatus;
use App\Models\Pole;
use App\Models\Role;
use App\Models\State;
use App\Services\ApprovedService;
use App\Services\ApprovedsSourceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ApprovedController extends Controller
{
    private ApprovedService $service;

    private ApprovedsSourceService $fileService;

    public function __construct(ApprovedService $approvedService, ApprovedsSourceService $fileService)
    {
        $this->service = $approvedService;
        $this->fileService = $fileService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        //check access permission
        if (! Gate::allows('approved-list')) {
            abort(403);
        }

        //filters
        $filters = ModelFilterHelper::buildFilters($request, Approved::$accepted_filters);

        //get approved states
        $approvedStates = ApprovedState::all();

        $approveds = $this->service->list();

        return view('approved.index', compact('approveds', 'approvedStates', 'filters'))->with('i', (request()->input('page', 1) - 1) * 10);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create(Request $request)
    {
        //check access permission
        if (! Gate::allows('approved-store')) {
            abort(403);
        }

        $courses = Course::orderBy('name')->get();
        $roles = Role::orderBy('name')->get();
        $poles = Pole::orderBy('name')->get();

        return view('approved.create', compact('courses', 'roles', 'poles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreApprovedRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreApprovedRequest $request)
    {
        //check access permission
        if (! Gate::allows('approved-store')) {
            abort(403);
        }

        try {
            $this->service->create($request->validated());
        } catch (\Exception $e) {
            return redirect()->route('approveds.index')->withErrors(['noStore' => 'Não foi possível salvar o Aprovado: ' . $e->getMessage()]);
        }

        return redirect()->route('approveds.index')->with('success', 'Aprovado criado com sucesso.');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function createManyStep1(Request $request) // import spreadsheet file view
    {
        //check access permission
        if (! Gate::allows('approved-store')) {
            abort(403);
        }

        return view('approved.createMany');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  ImportApprovedsFileRequest  $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeManyStep1(ImportApprovedsFileRequest $request)
    {
        //check access permission
        if (! Gate::allows('approved-store')) {
            abort(403);
        }

        try {
            $importedApproveds = $this->fileService->importApprovedsFromFile($request->file('file'));

            session(['importedApproveds' => $importedApproveds]);
        } catch (\Exception $e) {
            return back()->withErrors($e->getMessage());
        }

        return redirect()->route('approveds.createMany.step2')->with('success', 'Arquivo importado com sucesso.');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function createManyStep2(Request $request) // import spreadsheet file view
    {
        //check access permission
        if (! Gate::allows('approved-store')) {
            abort(403);
        }

        $roles = Role::orderBy('name')->get();
        $courses = Course::orderBy('name')->get();
        $poles = Pole::orderBy('name')->get();

        $importedApproveds = session('importedApproveds');

        return view('approved.review', compact('importedApproveds', 'roles', 'courses', 'poles'));
    }

    /**
     * Undocumented function
     *
     * @param StoreApprovedsRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeManyStep2(StoreApprovedsRequest $request)
    {
        //check access permission
        if (! Gate::allows('approved-store')) {
            abort(403);
        }

        try {
            $this->service->batchStore($request->validated());
        } catch (\Exception $e) {
            return back()->withErrors(['noStore' => 'Não foi possível salvar o(s) aprovado(s): ' . $e->getMessage()]);
        }

        session()->forget('importedApproveds');

        return redirect()->route('approveds.index')->with('success', 'Aprovados importados com sucesso.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Approved  $approved
     *
     * @return \Illuminate\Http\Response
     */
    /* public function show(Approved $approved)
    {
    } */

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Approved  $approved
     *
     * @return \Illuminate\Http\Response
     */
    /* public function edit(Approved $approved)
    {
    } */

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Approved  $approved
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Approved $approved)
    {
        //check access permission
        if (! Gate::allows('approved-update-state')) {
            abort(403);
        }

        try {
            $this->service->changeState($request->all(), $approved);
        } catch (\Exception $e) {
            return back()->withErrors(['noStore' => 'Não foi possível salvar o Aprovado: ' . $e->getMessage()]);
        }

        return redirect()->route('approveds.index')->with('success', 'Aprovado alterado com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Approved  $approved
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Approved $approved, Request $request)
    {
        //check access permission
        if (! Gate::allows('approved-destroy')) {
            abort(403);
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
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function designate(Request $request, Approved $approved)
    {
        //check access permission
        if (! Gate::allows('approved-designate')) {
            abort(403);
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
        $employee = new Employee(
            [
                'name' => $approved->name,
                'email' => $approved->email,
                'area_code' => $approved->area_code,
                'phone' => $approved->phone,
                'mobile' => $approved->mobile,
            ]
        );

        $fromApproved = true;

        EmployeeDesignated::dispatch($employee);

        return view('approved.designate', compact('genders', 'birthStates', 'documentTypes', 'maritalStatuses', 'addressStates', 'employee', 'fromApproved'));
    }
}
