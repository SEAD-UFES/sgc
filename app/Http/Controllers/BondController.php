<?php

namespace App\Http\Controllers;

use App\Helpers\ModelFilterHelper;
use App\Helpers\SgcLogHelper;
use App\Http\Requests\ReviewBondRequest;
use App\Http\Requests\StoreBondRequest;
use App\Http\Requests\UpdateBondRequest;
use App\Models\Bond;
use App\Models\BondDocument;
use App\Models\Course;
use App\Models\Document;
use App\Models\Employee;
use App\Models\Pole;
use App\Models\Role;
use App\Services\BondService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class BondController extends Controller
{
    private BondService $service;

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
        if (! Gate::allows('bond-list')) {
            SgcLogHelper::logBadAttemptOnUri($request, 403);
            abort(403);
        }

        //filters
        $filters = ModelFilterHelper::buildFilters($request, Bond::$accepted_filters);

        $bonds = $this->service->list();

        return view('bond.index', compact('bonds', 'filters'))->with('i', (request()->input('page', 1) - 1) * 10);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //check access permission
        if (! Gate::allows('bond-create')) {
            SgcLogHelper::logBadAttemptOnUri($request, 403);
            abort(403);
        }

        $employees = Employee::orderBy('name')->get();
        $roles = Role::orderBy('name')->get();
        $poles = Pole::orderBy('name')->get();

        //get only allowed courses
        /* $courses = Course::orderBy('name')->get();
        foreach ($courses as $key => $course) {
            if (!Gate::allows('bond-store-course_id', $course->id)) {
                $courses->forget($key);
            }
        } */

        $courses = $this->getAllowedCourses();

        return view('bond.create', compact('employees', 'roles', 'courses', 'poles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(StoreBondRequest $request)
    {
        //check access permission
        if (! Gate::allows('bond-create')) {
            SgcLogHelper::logBadAttemptOnUri($request, 403);
            abort(403);
        }

        //user can only store bonds with allowed course_ids
        if (! Gate::allows('bond-store-course_id', $request->course_id)) {
            return redirect()->route('bonds.index')->withErrors('O usuário não pode escolher esse curso.');
        }

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
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Bond $bond, Request $request)
    {
        //check access permission
        if (! Gate::allows('bond-show')) {
            SgcLogHelper::logBadAttemptOnUri($request, 403);
            abort(403);
        }

        $this->service->read($bond);

        $documents = Document::whereHasMorph('documentable', BondDocument::class, function ($query) use ($bond) {
            $query->where('bond_id', $bond->id);
        })->with('documentable')->orderBy('updated_at', 'desc')->get();

        return view('bond.show', compact(['bond', 'documents']));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Bond  $bond
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Bond $bond, Request $request)
    {
        //check access permission
        if (! Gate::allows('bond-update', $bond)) {
            SgcLogHelper::logBadAttemptOnUri($request, 403);
            abort(403);
        }

        //get only allowed courses
        /* $courses = Course::orderBy('name')->get();
        foreach ($courses as $key => $course) {
            if (!Gate::allows('bond-store-course_id', $course->id)) {
                $courses->forget($key);
            }
        } */

        $courses = $this->getAllowedCourses();

        $employees = Employee::orderBy('name')->get();
        $roles = Role::orderBy('name')->get();
        $poles = Pole::orderBy('name')->get();

        return view('bond.edit', compact('employees', 'roles', 'courses', 'poles', 'bond'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Bond  $bond
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateBondRequest $request, Bond $bond)
    {
        //check access permission
        if (! Gate::allows('bond-update', $bond)) {
            SgcLogHelper::logBadAttemptOnUri($request, 403);
            abort(403);
        }

        //user can only update bonds to allowed course_ids
        if (! Gate::allows('bond-store-course_id', $request->course_id)) {
            return back()->withErrors('courses', 'O usuário não pode escolher este curso.');
        }

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
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Bond $bond, Request $request)
    {
        //check access permission
        if (! Gate::allows('bond-destroy')) {
            SgcLogHelper::logBadAttemptOnUri($request, 403);
            abort(403);
        }

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
     *
     * @return \Illuminate\Http\Response
     */
    public function review(ReviewBondRequest $request, Bond $bond)
    {
        //check access permission
        if (! Gate::allows('bond-review')) {
            SgcLogHelper::logBadAttemptOnUri($request, 403);
            abort(403);
        }

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
     *
     * @return \Illuminate\Http\Response
     */
    public function requestReview(Request $request, Bond $bond)
    {
        //check access permission
        if (! Gate::allows('bond-requestReview')) {
            SgcLogHelper::logBadAttemptOnUri($request, 403);
            abort(403);
        }

        $bond = $this->service->requestReview($request->all(), $bond);

        return redirect()->route('bonds.show', $bond->id)->with('success', 'Revisão de vínculo solicitada.');
    }

    /** @return Collection  */
    private function getAllowedCourses(): Collection
    {
        $courses = Course::orderBy('name')->get();
        foreach ($courses as $key => $course) {
            if (! Gate::allows('bond-store-course_id', $course->id)) {
                $courses->forget($key);
            }
        }

        return $courses;
    }
}
