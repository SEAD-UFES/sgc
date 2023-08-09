<?php

namespace App\Http\Controllers;

use App\Http\Requests\InstitutionalDetail\CreateInstitutionalDetailRequest;
use App\Http\Requests\InstitutionalDetail\EditInstitutionalDetailRequest;
use App\Http\Requests\InstitutionalDetail\StoreInstitutionalDetailRequest;
use App\Http\Requests\InstitutionalDetail\UpdateInstitutionalDetailRequest;
use App\Models\Employee;
use App\Services\InstitutionalDetailService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class InstitutionalDetailController extends Controller
{
    public function __construct(private InstitutionalDetailService $service)
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
            $this->service->create($request->toDto(), $employee);
        } catch (\Exception $exception) {
            return redirect()->route('employees.show', $employee->id)->withErrors(['noStore' => 'Não foi possível salvar os Detalhes Intitucionais: ' . $exception->getMessage()]);
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

        return view('institutionalDetail.edit', ['institutionalDetail' => $institutionalDetail, 'employee' => $employee]);
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
        try {
            $this->service->update($request->toDto(), $employee);
        } catch (\Exception $exception) {
            return back()->withErrors(['noStore' => 'Não foi possível salvar os Detalhes Institucionais: ' . $exception->getMessage()]);
        }

        return redirect()->route('employees.show', $employee->id)->with('success', 'Detalhes Institucionais atualizados com sucesso.');
    }
}
