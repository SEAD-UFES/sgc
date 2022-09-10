<?php

namespace App\Http\Controllers;

use App\Http\Requests\Responsibility\CreateResponsibilityRequest;
use App\Http\Requests\Responsibility\DestroyResponsibilityRequest;
use App\Http\Requests\Responsibility\EditResponsibilityRequest;
use App\Http\Requests\Responsibility\IndexResponsibilityRequest;
use App\Http\Requests\Responsibility\ShowResponsibilityRequest;
use App\Http\Requests\Responsibility\StoreResponsibilityRequest;
use App\Http\Requests\Responsibility\UpdateResponsibilityRequest;
use App\Models\Responsibility;
use App\Services\ResponsibilityService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ResponsibilityController extends Controller
{
    public function __construct(private ResponsibilityService $service)
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @param IndexResponsibilityRequest $request
     *
     * @return View
     */
    public function index(IndexResponsibilityRequest $request): View
    {
        $responsibilities = $this->service->list();

        return view('responsibility.index', compact('responsibilities'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param CreateResponsibilityRequest $request
     *
     * @return View
     */
    public function create(CreateResponsibilityRequest $request): View
    {
        return view('responsibility.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreResponsibilityRequest  $request
     *
     * @return RedirectResponse
     */
    public function store(StoreResponsibilityRequest $request): RedirectResponse
    {
        try {
            $this->service->create($request->validated());
        } catch (\Exception $e) {
            return redirect()->route('responsibility.index')->withErrors(['noStore' => 'Não foi possível salvar a Atribuição de Papel: ' . $e->getMessage()]);
        }

        return redirect()->route('responsibility.index')->with('success', 'Atribuição de Papel criada com sucesso.');
    }

    /**
     * Display the specified resource.
     *
     * @param  ShowResponsibilityRequest $request
     * @param  Responsibility  $responsibility
     *
     * @return View
     */
    public function show(ShowResponsibilityRequest $request, Responsibility $responsibility): View
    {
        $responsibility = $this->service->read($responsibility);

        return view('responsibility.show', compact('responsibility'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  EditResponsibilityRequest $request
     * @param  Responsibility  $responsibility
     *
     * @return View
     */
    public function edit(EditResponsibilityRequest $request, Responsibility $responsibility): View
    {
        return view('responsibility.edit', compact('responsibility'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateResponsibilityRequest  $request
     * @param  Responsibility  $responsibility
     *
     * @return RedirectResponse
     */
    public function update(UpdateResponsibilityRequest $request, Responsibility $responsibility): RedirectResponse
    {
        try {
            $responsibility = $this->service->update($request->validated(), $responsibility);
        } catch (\Exception $e) {
            return back()->withErrors(['noStore' => 'Não foi possível salvar a Atribuição de Papel: ' . $e->getMessage()]);
        }

        return redirect()->route('responsibility.index')->with('success', 'Atribuição de Papel atualizada com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  DestroyResponsibilityRequest  $request
     * @param  Responsibility  $responsibility
     *
     * @return RedirectResponse
     */
    public function destroy(DestroyResponsibilityRequest $request, Responsibility $responsibility): RedirectResponse
    {
        try {
            $this->service->delete($responsibility);
        } catch (\Exception $e) {
            return back()->withErrors(['noDestroy' => 'Não foi possível excluir a atribuição de papel: ' . $e->getMessage()]);
        }

        return redirect()->route('responsibility.index')->with('success', 'Atribuição de papel excluído com sucesso.');
    }
}
