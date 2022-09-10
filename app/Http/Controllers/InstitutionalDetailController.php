<?php

namespace App\Http\Controllers;

use App\Http\Requests\InstitutionalDetail\CreateInstitutionalDetailRequest;
use App\Http\Requests\InstitutionalDetail\EditInstitutionalDetailRequest;
use App\Http\Requests\InstitutionalDetail\StoreInstitutionalDetailRequest;
use App\Http\Requests\InstitutionalDetail\UpdateInstitutionalDetailRequest;
use App\Models\Employee;
use App\Models\InstitutionalDetail;
use App\Services\InstitutionalDetailService;
use Illuminate\Http\RedirectResponse;
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
     * @param CreateInstitutionalDetailRequest $request
     *
     * @return View
     */
    public function create(CreateInstitutionalDetailRequest $request): View
    {
        return view('institutionalDetail.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreInstitutionalDetailRequest  $request
     * @param Employee  $employee
     *
     * @return RedirectResponse
     */
    public function store(StoreInstitutionalDetailRequest $request, Employee $employee): RedirectResponse
    {
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
     * @param EditInstitutionalDetailRequest $request
     * @param  Employee $employee
     *
     * @return View
     */
    public function edit(EditInstitutionalDetailRequest $request, Employee $employee): View
    {
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
}
