<?php

namespace App\Http\Controllers;

use App\Helpers\ModelFilterHelper;
use App\Helpers\SgcLogHelper;
use App\Http\Requests\StorePoleRequest;
use App\Http\Requests\UpdatePoleRequest;
use App\Models\Pole;
use App\Services\PoleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PoleController extends Controller
{
    private PoleService $service;

    public function __construct(PoleService $poleService)
    {
        $this->service = $poleService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //check access permission
        if (! Gate::allows('pole-list')) {
            abort(403);
        }

        //filters
        $filters = ModelFilterHelper::buildFilters($request, Pole::$accepted_filters);

        $poles = $this->service->list();

        return view('pole.index', compact('poles', 'filters'))->with('i', (request()->input('page', 1) - 1) * 10);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //check access permission
        if (! Gate::allows('pole-store')) {
            abort(403);
        }

        return view('pole.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(StorePoleRequest $request)
    {
        //check access permission
        if (! Gate::allows('pole-store')) {
            abort(403);
        }

        try {
            $this->service->create($request->validated());
        } catch (\Exception $e) {
            return redirect()->route('poles.index')->withErrors(['noStore' => 'Não foi possível salvar o Polo: ' . $e->getMessage()]);
        }

        return redirect()->route('poles.index')->with('success', 'Polo criado com sucesso.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Pole  $pole
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Pole $pole, Request $request)
    {
        //check access permission
        if (! Gate::allows('pole-show')) {
            abort(403);
        }

        $this->service->read($pole);

        return view('pole.show', compact('pole'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Pole  $pole
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Pole $pole, Request $request)
    {
        //check access permission
        if (! Gate::allows('pole-update')) {
            abort(403);
        }

        return view('pole.edit', compact('pole'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Pole  $pole
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePoleRequest $request, Pole $pole)
    {
        //check access permission
        if (! Gate::allows('pole-update')) {
            abort(403);
        }

        try {
            $pole = $this->service->update($request->validated(), $pole);
        } catch (\Exception $e) {
            return back()->withErrors(['noStore' => 'Não foi possível salvar o Polo: ' . $e->getMessage()]);
        }

        return redirect()->route('poles.index')->with('success', 'Polo atualizado com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Pole  $pole
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pole $pole, Request $request)
    {
        //check access permission
        if (! Gate::allows('pole-destroy')) {
            abort(403);
        }

        try {
            $this->service->delete($pole);
        } catch (\Exception $e) {
            return back()->withErrors(['noDestroy' => 'Não foi possível salvar o Polo: ' . $e->getMessage()]);
        }

        return redirect()->route('poles.index')->with('success', 'Polo excluído com sucesso.');
    }
}
