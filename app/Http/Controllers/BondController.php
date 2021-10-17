<?php

namespace App\Http\Controllers;

use App\Models\Bond;
use App\Models\Pole;
use App\Models\Role;
use App\Models\Course;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Services\BondService;
use App\CustomClasses\SgcLogger;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\StoreBondRequest;
use App\Http\Requests\ReviewBondRequest;
use App\Http\Requests\UpdateBondRequest;
use App\CustomClasses\ModelFilterHelpers;

class BondController extends Controller
{
    public function __construct(BondService $bondService)
    {
        $this->service = $bondService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //check access permission
        if (!Gate::allows('bond-list')) return response()->view('access.denied')->setStatusCode(401);

        //filters
        $filters = ModelFilterHelpers::buildFilters($request, Bond::$accepted_filters);

        $bonds = $this->service->list();

        return view('bond.index', compact('bonds', 'filters'))->with('i', (request()->input('page', 1) - 1) * 10);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //check access permission
        if (!Gate::allows('bond-create')) return response()->view('access.denied')->setStatusCode(401);

        $employees = Employee::orderBy('name')->get();
        $roles = Role::orderBy('name')->get();
        $poles = Pole::orderBy('name')->get();

        //get only allowed courses
        $courses = Course::orderBy('name')->get();
        foreach ($courses as $key => $course) if (!Gate::allows('bond-store-course_id', $course->id)) $courses->forget($key);

        SgcLogger::writeLog(target: 'Bond');

        return view('bond.create', compact('employees', 'roles', 'courses', 'poles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreBondRequest $request)
    {
        //check access permission
        if (!Gate::allows('bond-create')) return response()->view('access.denied')->setStatusCode(401);

        //user can only store bonds with allowed course_ids 
        if (!Gate::allows('bond-store-course_id', $request->course_id)) return redirect()->route('bonds.index')->withErrors('O usuário não pode escolher esse curso.');

        try {
            $this->service->create($request->validated());
        } catch (\Exception $e) {
            return redirect()->route('bonds.index')->withErrors(['noStore' => 'Não foi possível salvar o Vínculo: ' . $e->getMessage()]);
        }

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
        //check access permission
        if (!Gate::allows('bond-show')) return response()->view('access.denied')->setStatusCode(401);

        SgcLogger::writeLog(target: $bond);

        $documents = $bond->bondDocuments()->orderBy('updated_at', 'DESC')->get();

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
        //check access permission
        if (!Gate::allows('bond-update', $bond)) return response()->view('access.denied')->setStatusCode(401);

        //get only allowed courses
        $courses = Course::orderBy('name')->get();
        foreach ($courses as $key => $course) if (!Gate::allows('bond-store-course_id', $course->id)) $courses->forget($key);
        
        $employees = Employee::orderBy('name')->get();
        $roles = Role::orderBy('name')->get();
        $poles = Pole::orderBy('name')->get();

        SgcLogger::writeLog(target: $bond);

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
        //check access permission
        if (!Gate::allows('bond-update', $bond)) return response()->view('access.denied')->setStatusCode(401);

        //user can only update bonds to allowed course_ids 
        if (!Gate::allows('bond-store-course_id', $request->course_id)) return back()->withErrors('courses', 'O usuário não pode escolher este curso.');
        
        try {
            $bond = $this->service->update($request->validated(), $bond);
        } catch (\Exception $e) {
            return back()->withErrors(['noStore' => 'Não foi possível salvar o vínculo: ' . $e->getMessage()]);
        }

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
        //check access permission
        if (!Gate::allows('bond-destroy')) return response()->view('access.denied')->setStatusCode(401);

        try {
            $this->service->delete($bond);
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
        //check access permission
        if (!Gate::allows('bond-review')) return response()->view('access.denied')->setStatusCode(401);
        
        try {
            $this->service->review($request->validated(), $bond);
        } catch (\Exception $e) {
            return redirect()->route('bonds.show', $bond)->withErrors(['noStore' => 'Não foi possível salvar o vínculo: ' . $e->getMessage()]);
        }

        return redirect()->route('bonds.show', $bond)->with('success', 'Vínculo atualizado com sucesso.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Bond  $bond
     * @return \Illuminate\Http\Response
     */
    public function requestReview(Request $request, Bond $bond)
    {
        //check access permission
        if (!Gate::allows('bond-requestReview')) return response()->view('access.denied')->setStatusCode(401);
        
        $bond = $this->service->requestReview($request->all(), $bond);

        return redirect()->route('bonds.show', $bond->id)->with('success', 'Revisão de vínculo solicitada.');
    }
}
