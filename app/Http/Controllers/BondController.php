<?php

namespace App\Http\Controllers;

use App\Http\Requests\Bond\CreateBondRequest;
use App\Http\Requests\Bond\DestroyBondRequest;
use App\Http\Requests\Bond\EditBondRequest;
use App\Http\Requests\Bond\IndexBondRequest;
use App\Http\Requests\Bond\ShowBondRequest;
use App\Http\Requests\Bond\StoreBondRequest;
use App\Http\Requests\Bond\UpdateBondRequest;
use App\Models\Bond;
use App\Models\BondDocument;
use App\Models\Document;
use App\Services\BondService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class BondController extends Controller
{
    public function __construct(private BondService $service)
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @param IndexBondRequest $request
     *
     * @return View
     */
    public function index(IndexBondRequest $request): View
    {
        $bonds = $this->service->list();

        return view('bond.index', compact('bonds'))->with('i', (request()->input('page', 1) - 1) * 10);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param CreateBondRequest $request
     *
     * @return View
     */
    public function create(CreateBondRequest $request): View
    {
        return view('bond.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreBondRequest  $request
     *
     * @return RedirectResponse
     */
    public function store(StoreBondRequest $request): RedirectResponse
    {
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
     * @param  Bond  $bond
     *
     * @param ShowBondRequest $request
     *
     * @return View
     */
    public function show(ShowBondRequest $request, Bond $bond): View
    {
        $this->service->read($bond);

        $documents = Document::whereHasMorph('documentable', BondDocument::class, static function ($query) use ($bond) {
            $query->where('bond_id', $bond->id);
        })->with('documentable')->orderBy('updated_at', 'desc')->get();

        return view('bond.show', compact(['bond', 'documents']));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Bond  $bond
     *
     * @param EditBondRequest $request
     *
     * @return View
     */
    public function edit(EditBondRequest $request, Bond $bond): View
    {
        return view('bond.edit', compact('bond'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateBondRequest  $request
     * @param  Bond  $bond
     *
     * @return RedirectResponse
     */
    public function update(UpdateBondRequest $request, Bond $bond): RedirectResponse
    {
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
     * @param  Bond  $bond
     *
     * @param DestroyBondRequest $request
     *
     * @return RedirectResponse
     */
    public function destroy(DestroyBondRequest $request, Bond $bond): RedirectResponse
    {
        try {
            $this->service->delete($bond);
        } catch (\Exception $e) {
            return back()->withErrors(['noDestroy' => 'Não foi possível excluir o vínculo: ' . $e->getMessage()]);
        }

        return redirect()->route('bonds.index')->with('success', 'Vínculo excluído com sucesso.');
    }
}
