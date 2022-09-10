<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmployeeDocument\batch\CreateEmployeeDocumentBatchRequest;
use App\Http\Requests\EmployeeDocument\batch\Store2EmployeeDocumentBatchRequest;
use App\Http\Requests\EmployeeDocument\batch\StoreEmployeeDocumentBatchRequest;
use App\Models\Employee;
use App\Services\EmployeeDocumentService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class EmployeeDocumentBatchController extends Controller
{
    public function __construct(private EmployeeDocumentService $service)
    {
    }

    /**
     * @param CreateEmployeeDocumentBatchRequest $request
     *
     * @return View
     */
    public function create(CreateEmployeeDocumentBatchRequest $request): View
    {
        $id = $request->id ?? null;
        $employees = is_null($id)
            ? Employee::orderBy('name')->get()
            : Employee::where('id', $id)->get();

        return view('employee.document.create-many-1', compact('employees', 'id'));
    }

    /**
     * @param StoreEmployeeDocumentBatchRequest $request
     *
     * @return RedirectResponse|View
     */
    public function store(StoreEmployeeDocumentBatchRequest $request): RedirectResponse|View
    {
        try {
            $employeeDocuments = $this->service->createManyDocumentsStep1($request->validated());
        } catch (Exception $e) {
            return redirect()->back()->withErrors('Erro ao tentar obter arquivos: ' . $e->getMessage());
        }

        return view('employee.document.create-many-2', compact('employeeDocuments'));
    }

    /**
     * @param Store2EmployeeDocumentBatchRequest $request
     *
     * @return RedirectResponse
     */
    public function store2(Store2EmployeeDocumentBatchRequest $request): RedirectResponse
    {
        $this->service->createManyDocumentsStep2($request->all());

        return redirect()->route('employeesDocuments.index')->with('success', 'Arquivos importados com sucesso.');
    }
}
