<?php

namespace App\Http\Controllers;

use App\Models\Bond;
use App\Models\User;
use App\Models\UserType;
use App\Models\Role;
use App\Models\Course;
use App\Models\Pole;
use Illuminate\Http\Request;
use App\CustomClasses\SgcLogger;
use App\Models\Employee;
use App\Http\Requests\StoreBondRequest;
use App\Http\Requests\UpdateBondRequest;
use App\Models\EmployeeDocument;
use App\Models\BondDocument;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewBondNotification;
use App\Http\Requests\ReviewBondRequest;
use App\Notifications\BondImpededNotification;

class BondController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $bonds = Bond::with(['employee', 'course', 'role', 'pole'])->paginate(10); //->orderBy('employee')
        //dd($bonds);
        SgcLogger::writeLog('Bond');

        return view('bond.index', compact('bonds'))->with('i', (request()->input('page', 1) - 1) * 10);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $employees = Employee::orderBy('name')->get();
        $roles = Role::orderBy('name')->get();
        $courses = Course::orderBy('name')->get();
        $poles = Pole::orderBy('name')->get();
        $bond = new Bond;

        SgcLogger::writeLog('Bond');

        return view('bond.create', compact('employees', 'roles', 'courses', 'poles', 'bond'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreBondRequest $request)
    {
        $bond = new Bond;

        $bond->employee_id = $request->employees;
        $bond->role_id = $request->roles;
        $bond->course_id = $request->courses;
        $bond->pole_id = $request->poles;
        $bond->begin = $request->begin;
        $bond->end = $request->end;
        $bond->terminated_on = null;
        $bond->volunteer = $request->has('volunteer');
        $bond->impediment = false;
        $bond->impediment_description = $request->impedimentDescription;
        $bond->uaba_checked_on = null;

        $bond->save();

        $documents = EmployeeDocument::where('employee_id', $bond->employee_id)->get();
        foreach ($documents as $doc) {
            $bondDocument = new BondDocument();
            $bondDocument->original_name = $doc->original_name;
            $bondDocument->file_data = $doc->file_data;
            $bondDocument->document_type_id = $doc->documentType->id;
            $bondDocument->bond_id = $bond->id;

            $bondDocument->save();
        }

        SgcLogger::writeLog($bond);

        $grantorAssistants = UserType::with('users')->firstWhere('acronym', 'ass')->users;
        Notification::send($grantorAssistants, new NewBondNotification($bond));

        return redirect()->route('bonds.index')->with('success', 'Vínculo criado com sucesso.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Bond  $bond
     * @return \Illuminate\Http\Response
     */
    public function show(Bond $bond)
    {
        $documents = $bond->bondDocuments;
        return view('bond.show', compact('bond', 'documents'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Bond  $bond
     * @return \Illuminate\Http\Response
     */
    public function edit(Bond $bond)
    {
        $employees = Employee::orderBy('name')->get();
        $roles = Role::orderBy('name')->get();
        $courses = Course::orderBy('name')->get();
        $poles = Pole::orderBy('name')->get();

        SgcLogger::writeLog($bond);

        return view('bond.edit', compact('employees', 'roles', 'courses', 'poles', 'bond'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Bond  $bond
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateBondRequest $request, Bond $bond)
    {
        $bond->employee_id = $request->employees;
        $bond->role_id = $request->roles;
        $bond->course_id = $request->courses;
        $bond->pole_id = $request->poles;
        $bond->begin = $request->begin;
        $bond->end = $request->end;
        $bond->volunteer = $request->has('volunteer');

        try {
            $bond->save();
        } catch (\Exception $e) {
            return back()->withErrors(['noStore' => 'Não foi possível salvar o vínculo: ' . $e->getMessage()]);
        }

        SgcLogger::writeLog($bond);

        return redirect()->route('bonds.index')->with('success', 'Vínculo atualizado com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Bond  $bond
     * @return \Illuminate\Http\Response
     */
    public function destroy(Bond $bond)
    {
        SgcLogger::writeLog($bond);

        try {
            $bond->delete();
        } catch (\Exception $e) {
            return back()->withErrors(['noDestroy' => 'Não foi possível excluir o vínculo: ' . $e->getMessage()]);
        }

        return redirect()->route('bonds.index')->with('success', 'Vínculo excluído com sucesso.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Bond  $bond
     * @return \Illuminate\Http\Response
     */
    public function review(ReviewBondRequest $request, Bond $bond)
    {
        $bond->impediment = ($request->impediment == '1') ? true : false;
        $bond->impediment_description = $request->impedimentDescription;
        $bond->uaba_checked_on = now();

        try {
            $bond->save();
        } catch (\Exception $e) {
            return redirect()->route('bonds.show', $bond)->withErrors(['noStore' => 'Não foi possível salvar o vínculo: ' . $e->getMessage()]);
        }

        SgcLogger::writeLog($bond, 'edit');

        $academicSecretaries = UserType::with('users')->Where('acronym', 'sec')->first()->users;
        //dd($academicSecretaries);

        $courseBonds = Bond::with(['employee'])->where('course_id', $bond->course->id)->get();
        $courseCoordinators = collect();
        foreach ($courseBonds as $courseBond)
            if ($courseBond->employee->isCoordinator() && !is_null($courseBond->employee->user))
                $courseCoordinators->push($courseBond->employee->user);

        $users = $academicSecretaries->merge($courseCoordinators);

        if ($bond->impediment == true)
            Notification::send($users, new BondImpededNotification($bond));

        return redirect()->route('bonds.show', $bond)->with('success', 'Vínculo atualizado com sucesso.');
    }
}
