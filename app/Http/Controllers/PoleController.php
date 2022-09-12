<?php

namespace App\Http\Controllers;

use App\Http\Requests\Pole\CreatePoleRequest;
use App\Http\Requests\Pole\DestroyPoleRequest;
use App\Http\Requests\Pole\EditPoleRequest;
use App\Http\Requests\Pole\IndexPoleRequest;
use App\Http\Requests\Pole\ShowPoleRequest;
use App\Http\Requests\Pole\StorePoleRequest;
use App\Http\Requests\Pole\UpdatePoleRequest;
use App\Models\Pole;
use App\Services\PoleService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PoleController extends Controller
{
    public function __construct(private PoleService $service)
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @param IndexPoleRequest $request
     *
     * @return View
     */
    public function index(IndexPoleRequest $request): View
    {
        $poles = $this->service->list();

        return view('pole.index', compact('poles'))->with('i', (request()->input('page', 1) - 1) * 10);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param CreatePoleRequest $request
     *
     * @return View
     */
    public function create(CreatePoleRequest $request): View
    {
        return view('pole.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StorePoleRequest  $request
     *
     * @return RedirectResponse
     */
    public function store(StorePoleRequest $request): RedirectResponse
    {
        try {
            $this->service->create($request->toDto());
        } catch (\Exception $e) {
            return redirect()->route('poles.index')->withErrors(['noStore' => 'Não foi possível salvar o Polo: ' . $e->getMessage()]);
        }

        return redirect()->route('poles.index')->with('success', 'Polo criado com sucesso.');
    }

    /**
     * Display the specified resource.
     *
     * @param  ShowPoleRequest  $request
     * @param  Pole  $pole
     *
     * @return View
     */
    public function show(ShowPoleRequest $request, Pole $pole): View
    {
        $this->service->read($pole);

        return view('pole.show', compact('pole'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  EditPoleRequest  $request
     * @param  Pole  $pole
     *
     * @return View
     */
    public function edit(EditPoleRequest $request, Pole $pole): View
    {
        return view('pole.edit', compact('pole'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdatePoleRequest  $request
     * @param  Pole  $pole
     *
     * @return RedirectResponse
     */
    public function update(UpdatePoleRequest $request, Pole $pole): RedirectResponse
    {
        try {
            $pole = $this->service->update($request->toDto(), $pole);
        } catch (\Exception $e) {
            return back()->withErrors(['noStore' => 'Não foi possível salvar o Polo: ' . $e->getMessage()]);
        }

        return redirect()->route('poles.index')->with('success', 'Polo atualizado com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  DestroyPoleRequest  $request
     * @param  Pole  $pole
     *
     * @return RedirectResponse
     */
    public function destroy(DestroyPoleRequest $request, Pole $pole): RedirectResponse
    {
        try {
            $this->service->delete($pole);
        } catch (\Exception $e) {
            return back()->withErrors(['noDestroy' => 'Não foi possível salvar o Polo: ' . $e->getMessage()]);
        }

        return redirect()->route('poles.index')->with('success', 'Polo excluído com sucesso.');
    }
}
