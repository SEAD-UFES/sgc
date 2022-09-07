<?php

namespace App\Http\Controllers;

use App\Helpers\ModelFilterHelper;
use App\Http\Requests\Approved\CreateApprovedRequest;
use App\Http\Requests\Approved\DestroyApprovedRequest;
use App\Http\Requests\Approved\IndexApprovedRequest;
use App\Http\Requests\Approved\StoreApprovedRequest;
use App\Http\Requests\Approved\UpdateApprovedStateRequest;
use App\Models\Approved;
use App\Models\ApprovedState;
use App\Services\ApprovedService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ApprovedController extends Controller
{
    public function __construct(private ApprovedService $service)
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @param IndexApprovedRequest $request
     *
     * @return View
     */
    public function index(IndexApprovedRequest $request): View
    {
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
     * @param CreateApprovedRequest $request
     *
     * @return View
     */
    public function create(CreateApprovedRequest $request): View
    {
        return view('approved.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreApprovedRequest $request
     *
     * @return RedirectResponse
     */
    public function store(StoreApprovedRequest $request)
    {
        try {
            $this->service->create($request->validated());
        } catch (\Exception $e) {
            return redirect()->route('approveds.index')->withErrors(['noStore' => 'Não foi possível salvar o Aprovado: ' . $e->getMessage()]);
        }

        return redirect()->route('approveds.index')->with('success', 'Aprovado criado com sucesso.');
    }

    /**
     * Display the specified resource.
     *
     * @param  Approved  $approved
     *
     * @return Response
     */
    /* public function show(Approved $approved)
    {
    } */

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Approved  $approved
     *
     * @return Response
     */
    /* public function edit(Approved $approved)
    {
    } */

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateApprovedStateRequest  $request
     * @param  Approved  $approved
     *
     * @return RedirectResponse
     */
    public function update(UpdateApprovedStateRequest $request, Approved $approved): RedirectResponse
    {
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
     * @param DestroyApprovedRequest $request
     * @param  Approved  $approved
     *
     * @return RedirectResponse
     */
    public function destroy(DestroyApprovedRequest $request, Approved $approved): RedirectResponse
    {
        try {
            $this->service->delete($approved);
        } catch (\Exception $e) {
            return back()->withErrors(['noDestroy' => 'Não foi possível excluir o Aprovado: ' . $e->getMessage()]);
        }

        return redirect()->route('approveds.index')->with('success', 'Aprovado retirado da lista.');
    }
}
