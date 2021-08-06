<?php

namespace App\Http\Controllers;

use App\Models\Pole;
use Illuminate\Http\Request;
use App\CustomClasses\SgcLogger;
use App\Http\Requests\StorePoleRequest;
use App\Http\Requests\UpdatePoleRequest;
use App\CustomClasses\ModelFilterHelpers;

class PoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $poles_query = new Pole();

        //filters
        $filters = ModelFilterHelpers::buildFilters($request, Pole::$accepted_filters);
        $poles_query = $poles_query->AcceptRequest(Pole::$accepted_filters)->filter();

        //sort
        $poles_query = $poles_query->sortable(['name' => 'asc']);

        //get paginate and add querystring on paginate links
        $poles = $poles_query->paginate(10);
        $poles->appends($request->all());

        //write log
        SgcLogger::writeLog('Pole');

        return view('pole.index', compact('poles', 'filters'))->with('i', (request()->input('page', 1) - 1) * 10);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pole = new Pole;

        SgcLogger::writeLog('Pole');

        return view('pole.create', compact('pole'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePoleRequest $request)
    {
        $pole = new Pole;

        $pole->name = $request->name;
        $pole->description = $request->description;

        $pole->save();

        return redirect()->route('poles.index')->with('success', 'Polo criado com sucesso.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Pole  $pole
     * @return \Illuminate\Http\Response
     */
    public function show(Pole $pole)
    {
        return view('pole.show', compact('pole'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Pole  $pole
     * @return \Illuminate\Http\Response
     */
    public function edit(Pole $pole)
    {
        SgcLogger::writeLog($pole);

        return view('pole.edit', compact('pole'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Pole  $pole
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePoleRequest $request, Pole $pole)
    {
        $pole->name = $request->name;
        $pole->description = $request->description;

        try {
            $pole->save();
        } catch (\Exception $e) {
            return back()->withErrors(['noStore' => 'Não foi possível salvar o Polo: ' . $e->getMessage()]);
        }

        SgcLogger::writeLog($pole);

        return redirect()->route('poles.index')->with('success', 'Polo atualizado com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Pole  $pole
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pole $pole)
    {
        SgcLogger::writeLog($pole);

        try {
            $pole->delete();
        } catch (\Exception $e) {
            return back()->withErrors(['noDestroy' => 'Não foi possível salvar o Polo: ' . $e->getMessage()]);
        }

        return redirect()->route('poles.index')->with('success', 'Polo excluído com sucesso.');
    }
}
