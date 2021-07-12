<?php

namespace App\Http\Controllers;

use App\Models\Approved;
use Illuminate\Http\Request;
use App\CustomClasses\SgcLogger;
use App\Models\ApprovedState;
use App\Models\Gender;
use App\Models\State;
use App\Models\IdType;
use App\Models\MaritalStatus;
use App\Models\Employee;
use App\Imports\ApprovedsImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class ApprovedController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $approveds = Approved::with(['approvedState', 'course', 'pole', 'role'])->orderBy('name')->paginate(10);
        $approvedStates = ApprovedState::all();

        SgcLogger::writeLog('Employee');

        return view('approved.index', compact('approveds', 'approvedStates'))->with('i', (request()->input('page', 1) - 1) * 10);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
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
        $request->validate([
            'file' => 'required|mimes:csv,xlx,xls,xlsx|max:2048'
        ]);

        //dd($request);

        if ($request->file()) {
            $fileName = $request->file->getClientOriginalName();
            $filePath = $request->file('file')->storeAs('temp', $fileName, 'local');

            Excel::import(new ApprovedsImport, $filePath);/* 'Controle de inscriçoes_015_2021_Química_tutor.xlsx' */
            //dd($filePath);
            Storage::delete($filePath);
        }
        return redirect()->route('approveds.index')->with('success', 'Aprovados importados para o sistema.');
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
        SgcLogger::writeLog($approved);

        try {
            $approved->delete();
        } catch (\Exception $e) {
            return back()->withErrors(['noDestroy' => 'Não foi possível excluir o Aprovado: ' . $e->getMessage()]);
        }

        return redirect()->route('approveds.index')->with('success', 'Aprovado retirado da lista.');
    }

    public function changeState(Approved $approved, ApprovedState $state)
    {
        if ($state->name == 'Desistente') {
            $this->destroy($approved);
            return redirect()->route('approveds.index')->with('success', 'Aprovado retirado da lista.');
        } else {
            $approved->approved_state_id = $state->id;

            try {
                $approved->save();
            } catch (\Exception $e) {
                return back()->withErrors(['noStore' => 'Não foi possível salvar o Aprovado: ' . $e->getMessage()]);
            }

            SgcLogger::writeLog($approved, 'edit');

            return redirect()->route('approveds.index')->with('success', 'Aprovado alterado com sucesso.');
        }
    }

    public function designate(Approved $approved)
    {
        $existantEmployee = Employee::where('email', $approved->email)->first();
        if (is_null($existantEmployee)) {
            $genders = Gender::orderBy('name')->get();
            $birthStates = State::orderBy('name')->get();
            $idTypes = IdType::orderBy('name')->get();
            $maritalStatuses = MaritalStatus::orderBy('name')->get();
            $addressStates = State::orderBy('name')->get();

            $employee = new Employee;

            $employee->name = $approved->name;
            $employee->email = $approved->email;
            $employee->area_code = $approved->area_code;
            $employee->phone = $approved->phone;
            $employee->mobile = $approved->mobile;

            SgcLogger::writeLog('Employee', 'create');
            $this->destroy($approved); // How to destroy only if it was really converted to Employee?

            return view('employee.create', compact('genders', 'birthStates', 'idTypes', 'maritalStatuses', 'addressStates', 'employee'));
        } else {
            $email = $approved->email;
            $this->destroy($approved);
            return redirect()->route('approveds.index')->with('success', 'Colaborador de email [' . $email . '] já existente no sistema.');
        }
    }

    public function import()
    {

        Excel::import(new ApprovedsImport, 'Controle de inscriçoes_016_2021_Química_prof.xlsx');/* 'Controle de inscriçoes_015_2021_Química_tutor.xlsx' */

        return redirect()->route('approveds.index')->with('success', 'Aprovados importados para o sistema.');
    }
}
