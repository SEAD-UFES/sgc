<?php

namespace App\Http\Controllers;

use App\CustomClasses\ModelFilterHelpers;
use App\CustomClasses\SgcLogger;
use App\Http\Requests\StoreEmployeeDocumentRequest;
use App\Http\Requests\StoreEmployeeMultipleDocumentsRequest;
use App\Models\DocumentType;
use App\Models\Employee;
use App\Models\EmployeeDocument;
use App\Services\DocumentService;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use InvalidArgumentException;

class EmployeeDocumentController extends DocumentController
{
    /**
     * @param DocumentService $documentService
     *
     * @return void
     */
    public function __construct(DocumentService $documentService)
    {
        parent::__construct($documentService);
        $this->service->documentClass = EmployeeDocument::class;
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function employeesDocumentsIndex(Request $request)
    {
        //check access permission
        if (! Gate::allows('employeeDocument-list')) {
            SgcLogger::logBadAttemptOnUri($request->getRequestUri(), 403);
            abort(403);
        }

        $filters = ModelFilterHelpers::buildFilters($request, $this->service->documentClass::$accepted_filters);
        $documents = $this->service->list(sort: $request->query('sort'), direction: $request->query('direction'));

        return view('employee.document.index', compact('documents', 'filters'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function employeesDocumentsCreate(Request $request)
    {
        //check access permission
        if (! Gate::allows('employeeDocument-store')) {
            SgcLogger::logBadAttemptOnUri($request->getRequestUri(), 403);
            abort(403);
        }

        $documentTypes = DocumentType::orderBy('name')->get();
        $employees = Employee::all();

        return view('employee.document.create', compact('documentTypes', 'employees'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function employeesDocumentsStore(StoreEmployeeDocumentRequest $request)
    {
        //check access permission
        if (! Gate::allows('employeeDocument-store')) {
            SgcLogger::logBadAttemptOnUri($request->getRequestUri(), 403);
            abort(403);
        }

        $this->service->create($request->validated());

        return redirect()->route('employeesDocuments.index')->with('success', 'Arquivo importado com sucesso.');
    }

    /**
     * @param Request $request
     *
     * @return Response
     *
     * @throws BindingResolutionException
     * @throws InvalidArgumentException
     */
    public function employeesDocumentsCreateMany(Request $request)
    {
        //check access permission
        if (! Gate::allows('employeeDocument-store')) {
            SgcLogger::logBadAttemptOnUri($request->getRequestUri(), 403);
            abort(403);
        }

        $id = $request->id ?? null;
        $employees = ! is_null($id)
            ? Employee::where('id', $id)->get()
            : Employee::orderBy('name')->get();

        return view('employee.document.create-many-1', compact('employees', 'id'));
    }

    public function employeesDocumentsStoreMany1(StoreEmployeeMultipleDocumentsRequest $request)
    {
        //check access permission
        if (! Gate::allows('employeeDocument-store')) {
            SgcLogger::logBadAttemptOnUri($request->getRequestUri(), 403);
            abort(403);
        }

        $documentTypes = DocumentType::orderBy('name')->get();

        try {
            $employeeDocuments = $this->service->createManyDocumentsStep1($request->validated());
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Erro ao tentar obter arquivos: ' . $e->getMessage());
        }

        return view('employee.document.create-many-2', compact('employeeDocuments', 'documentTypes'));
    }

    public function employeesDocumentsStoreMany2(Request $request)
    {
        //check access permission
        if (! Gate::allows('employeeDocument-store')) {
            SgcLogger::logBadAttemptOnUri($request->getRequestUri(), 403);
            abort(403);
        }

        $this->service->createManyDocumentsStep2($request->all());

        return redirect()->route('employeesDocuments.index')->with('success', 'Arquivos importados com sucesso.');
    }

    public function employeesDocumentsExport(Employee $employee, Request $request)
    {
        //check access permission
        if (! Gate::allows('employeeDocument-download')) {
            SgcLogger::logBadAttemptOnUri($request->getRequestUri(), 403);
            abort(403);
        }

        try {
            $zipFileName = $this->service->exportEmployeeDocuments($employee);
        } catch (\Exception $e) {
            return redirect()->route('employees.show', $employee)->withErrors('Erro ao gerar o arquivo compactado: ' . $e->getMessage());
        }

        return response()->download($zipFileName)->deleteFileAfterSend(true);
    }
}
