<?php

namespace App\Http\Controllers;

use App\Events\InstitutionalLoginConfirmed;
use App\Http\Requests\StoreInstitutionalDetailRequest;
use App\Http\Requests\UpdateInstitutionalDetailRequest;
use App\Models\Bond;
use App\Models\Employee;
use App\Models\InstitutionalDetail;
use App\Services\InstitutionalDetailService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class InstitutionalDetailController extends Controller
{
    public function __construct(private InstitutionalDetailService $service)
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    public function index(): void
    {
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create(Request $request): View
    {
        //check access permission
        if (! Gate::allows('employee-store')) {
            abort(403);
        }

        return view('institutionalDetail.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreInstitutionalDetailRequest  $request
     *
     * @return RedirectResponse
     */
    public function store(StoreInstitutionalDetailRequest $request, Employee $employee): RedirectResponse
    {
        //check access permission
        if (! Gate::allows('employee-store')) {
            abort(403);
        }

        try {
            $this->service->create($request->validated(), $employee);
        } catch (\Exception $e) {
            return redirect()->route('employees.show', $employee->id)->withErrors(['noStore' => 'Não foi possível salvar os Detalhes Intitucionais: ' . $e->getMessage()]);
        }

        return redirect()->route('employees.show', $employee->id)->with('success', 'Detalhes Institucionais criados com sucesso.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Employee $employee
     *
     * @return View
     */
    public function edit(Request $request, Employee $employee): View
    {
        //check access permission
        if (! Gate::allows('employee-update')) {
            abort(403);
        }

        $institutionalDetail = $employee->institutionalDetail;

        return view('institutionalDetail.edit', compact('institutionalDetail', 'employee'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateInstitutionalDetailRequest  $request
     * @param  Employee $employee
     *
     * @return RedirectResponse
     */
    public function update(UpdateInstitutionalDetailRequest $request, Employee $employee): RedirectResponse
    {
        //check access permission
        if (! Gate::allows('employee-update')) {
            abort(403);
        }

        /**
         * @var InstitutionalDetail $institutionalDetail
         */
        $institutionalDetail = $employee->institutionalDetail;

        try {
            $institutionalDetail = $this->service->update($request->validated(), $institutionalDetail, $employee);
        } catch (\Exception $e) {
            return back()->withErrors(['noStore' => 'Não foi possível salvar os Detalhes Institucionais: ' . $e->getMessage()]);
        }

        return redirect()->route('employees.show', $employee->id)->with('success', 'Detalhes Institucionais atualizados com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  InstitutionalDetail  $institutionalDetail
     *
     * @return void
     */
    public function destroy(InstitutionalDetail $institutionalDetail): void
    {
    }

    /**
     * @param Request $request
     * @param Bond $bond
     *
     * @return RedirectResponse
     */
    public function sendNewEmployeeEmails(Bond $bond, Request $request): RedirectResponse
    {
        InstitutionalLoginConfirmed::dispatch($bond);

        return redirect()->route('bonds.show', $bond->id)->with('success', 'E-mails enviados para a fila de envio.');
    }
}
