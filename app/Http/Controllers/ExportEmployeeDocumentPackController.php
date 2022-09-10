<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmployeeDocument\ExportEmployeeDocumentPackRequest;
use App\Models\Employee;
use App\Services\EmployeeDocumentService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ExportEmployeeDocumentPackController extends Controller
{
    /**
     * @param EmployeeDocumentService $service
     *
     * @return void
     */
    public function __construct(private EmployeeDocumentService $service)
    {
    }

    /**
     * Handle the incoming request.
     *
     * @param  ExportEmployeeDocumentPackRequest  $request
     * @param  Employee  $employee
     *
     * @return BinaryFileResponse|RedirectResponse
     */
    public function __invoke(ExportEmployeeDocumentPackRequest $request, Employee $employee): BinaryFileResponse|RedirectResponse
    {
        try {
            $zipFileName = $this->service->exportDocuments($employee);
        } catch (Exception $e) {
            return redirect()->route('employees.show', $employee)->withErrors('Erro ao gerar o arquivo compactado: ' . $e->getMessage());
        }

        return response()->download($zipFileName)->deleteFileAfterSend(true);
    }
}
