<?php

namespace App\Http\Controllers;

use App\Models\Approved;
use Illuminate\Http\Request;
use App\CustomClasses\SgcLogger;
use App\Models\ApprovedState;
use App\Models\Gender;
use App\Models\State;
use App\Models\documentType;
use App\Models\MaritalStatus;
use App\Models\Employee;
use App\Imports\ApprovedsImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use App\Models\Role;
use App\Models\Course;
use App\Models\Pole;
use App\CustomClasses\ModelFilterHelpers;
use Illuminate\Support\Facades\Gate;

class ApprovedController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //check access permission
        if (!Gate::allows('approved-list')) return view('access.denied');

        $approveds_query = Approved::with(['approvedState', 'course', 'pole', 'role']);

        //filters
        $filters = ModelFilterHelpers::buildFilters($request, Approved::$accepted_filters);
        $approveds_query = $approveds_query->AcceptRequest(Approved::$accepted_filters)->filter();

        //sort
        $approveds_query = $approveds_query->sortable(['updated_at' => 'desc']);

        //get paginate and add querystring on paginate links
        $approveds = $approveds_query->paginate(10);
        $approveds->appends($request->all());

        //get approved states
        $approvedStates = ApprovedState::all();

        //write on log
        SgcLogger::writeLog('Employee');

        return view('approved.index', compact('approveds', 'approvedStates', 'filters'))->with('i', (request()->input('page', 1) - 1) * 10);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //check access permission
        if (!Gate::allows('approved-store')) return view('access.denied');

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
        if (!Gate::allows('approved-destroy')) return view('access.denied');

        SgcLogger::writeLog($approved);

        try {
            $approved->delete();
        } catch (\Exception $e) {
            return back()->withErrors(['noDestroy' => 'Não foi possível excluir o Aprovado: ' . $e->getMessage()]);
        }

        return redirect()->route('approveds.index')->with('success', 'Aprovado retirado da lista.');
    }

    public function changeState(Request $request, Approved $approved)
    {
        //check access permission
        if (!Gate::allows('approved-update-state')) return view('access.denied');

        $new_state_id = $request->states;

        $approved->approved_state_id = $new_state_id;

        try {
            $approved->save();
        } catch (\Exception $e) {
            return back()->withErrors(['noStore' => 'Não foi possível salvar o Aprovado: ' . $e->getMessage()]);
        }

        SgcLogger::writeLog($approved, 'edit');

        return redirect()->route('approveds.index')->with('success', 'Aprovado alterado com sucesso.');
    }

    public function designate(Request $request)
    {
        //check access permission
        if (!Gate::allows('approved-designate')) return view('access.denied');

        $approved = Approved::find($request->approvedId);
        $existantEmployee = Employee::where('email', $approved->email)->first();

        if (is_null($existantEmployee)) {
            $genders = Gender::orderBy('name')->get();
            $birthStates = State::orderBy('name')->get();
            $documentTypes = DocumentType::orderBy('name')->get();
            $maritalStatuses = MaritalStatus::orderBy('name')->get();
            $addressStates = State::orderBy('name')->get();

            $employee = new Employee;
            $employee->name = $approved->name;
            $employee->email = $approved->email;
            $employee->area_code = $approved->area_code;
            $employee->phone = $approved->phone;
            $employee->mobile = $approved->mobile;

            SgcLogger::writeLog('Employee', 'create');

            $this->destroy($approved);

            return view('approved.designate', compact('genders', 'birthStates', 'documentTypes', 'maritalStatuses', 'addressStates', 'employee'));
        } else {
            $email = $approved->email;

            $this->destroy($approved);

            return redirect()->route('approveds.index')->with('success', "Colaborador de email [$email] já existente no sistema. " . ($existantEmployee->hasDocuments() ? 'Com' : 'Sem') . ' documentos.');
        }
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,xlx,xls,xlsx|max:2048'
        ]);

        //check access permission
        if (!Gate::allows('approved-store')) return view('access.denied');

        $approveds = collect();
        $roles = Role::orderBy('name')->get();
        $courses = Course::orderBy('name')->get();
        $poles = Pole::orderBy('name')->get();

        if ($request->file()) {
            $fileName = $request->file->getClientOriginalName();
            $filePath = $request->file('file')->storeAs('temp', $fileName, 'local');

            Excel::import(new ApprovedsImport($approveds), $filePath);
            Storage::delete($filePath);
        }

        return view('approved.review', compact('approveds', 'roles', 'courses', 'poles'))->with('success', 'Aprovados importados da planilha.');
    }

    public function massStore(Request $request)
    {
        //check access permission
        if (!Gate::allows('approved-store')) return view('access.denied');

        $approvedsCount = $request->approvedsCount;
        for ($i = 0; $i < $approvedsCount; $i++) {
            if ($request->has('check_' . $i)) {
                $approved = new Approved();
                $approved->name = $request->input('name_' . $i);
                $approved->email = $request->input('email_' . $i);
                $approved->area_code = $request->input('area_' . $i);
                $approved->phone = $request->input('phone_' . $i);
                $approved->mobile = $request->input('mobile_' . $i);
                $approved->announcement = $request->input('announcement_' . $i);
                $approved->course_id = $request->input('courses_' . $i);
                $approved->role_id = $request->input('roles_' . $i);
                $approved->pole_id = $request->input('poles_' . $i);
                $approved->approved_state_id = 1;
                $approved->save();
            }
        }

        SgcLogger::writeLog('Mass Approveds', 'create');

        return redirect()->route('approveds.index')->with('success', 'Aprovados importados com sucesso.');
    }
}
