<?php

namespace App\Http\Controllers;

use App\Models\Bond;
use App\Models\Employee;
use App\Models\BondDocument;
use App\Models\DocumentType;
use Illuminate\Http\Request;
use App\Models\EmployeeDocument;
use App\Services\DocumentService;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Response;
use App\CustomClasses\ModelFilterHelpers;
use App\Http\Requests\StoreBondDocumentRequest;
use App\Http\Requests\StoreEmployeeDocumentRequest;
use App\Http\Requests\StoreBondMultipleDocumentsRequest;
use App\Http\Requests\StoreEmployeeMultipleDocumentsRequest;

class DocumentController extends Controller
{
    public function __construct(DocumentService $documentService)
    {
        $this->service = $documentService;
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function employeesDocumentsIndex(Request $request)
    {
        //check access permission
        if (!Gate::allows('employeeDocument-list')) return response()->view('access.denied')->setStatusCode(401);

        $this->service->documentClass = EmployeeDocument::class;

        //filters
        $filters = ModelFilterHelpers::buildFilters($request, $this->service->documentClass::$accepted_filters);

        $documents = $this->service->list(sort: $request->query('sort'), direction: $request->query('direction'));

        return view('employee.document.index', compact('documents', 'filters'));
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function bondsDocumentsIndex(Request $request)
    {
        //check access permission
        if (!Gate::allows('bondDocument-list')) return response()->view('access.denied')->setStatusCode(401);

        $this->service->documentClass = BondDocument::class;

        //filters
        $filters = ModelFilterHelpers::buildFilters($request, $this->service->documentClass::$accepted_filters);

        $documents = $this->service->list(sort: $request->query('sort'), direction: $request->query('direction'));

        return view('bond.document.index', compact('documents', 'filters'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function rightsIndex(Request $request)
    {
        //check access permission
        if (!Gate::allows('bondDocument-rights')) return response()->view('access.denied')->setStatusCode(401);

        //filters
        $filters = ModelFilterHelpers::buildFilters($request, BondDocument::$accepted_filters);

        $documents = $this->service->listRights(sort: $request->query('sort'), direction: $request->query('direction'));

        return view('reports.rightsIndex', compact('documents', 'filters'))->with('i', (request()->input('page', 1) - 1) * 10);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function employeesDocumentsCreate()
    {
        //check access permission
        if (!Gate::allows('employeeDocument-store')) return response()->view('access.denied')->setStatusCode(401);

        $documentTypes = DocumentType::orderBy('name')->get();
        $employees = Employee::all();

        return view('employee.document.create', compact('documentTypes', 'employees'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function bondsDocumentsCreate()
    {
        //check access permission
        if (!Gate::allows('bondDocument-store')) return response()->view('access.denied')->setStatusCode(401);

        $documentTypes = DocumentType::orderBy('name')->get();
        $bonds = Bond::all();

        return view('bond.document.create', compact('documentTypes', 'bonds'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function employeesDocumentsStore(StoreEmployeeDocumentRequest $request)
    {
        //check access permission
        if (!Gate::allows('employeeDocument-store')) return response()->view('access.denied')->setStatusCode(401);

        $this->service->documentClass = EmployeeDocument::class;
        $this->service->create($request->validated());

        return redirect()->route('employeesDocuments.index')->with('success', 'Arquivo importado com sucesso.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function bondsDocumentsStore(StoreBondDocumentRequest $request)
    {
        //check access permission
        if (!Gate::allows('bondDocument-store')) return response()->view('access.denied')->setStatusCode(401);

        $this->service->documentClass = BondDocument::class;
        $this->service->create($request->validated());

        return redirect()->route('bondsDocuments.index')->with('success', 'Arquivo importado com sucesso.');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function employeesDocumentsCreateMany(Request $request)
    {
        //check access permission
        if (!Gate::allows('employeeDocument-store')) return response()->view('access.denied')->setStatusCode(401);

        $id = $request->id ?? null;
        $employees = !is_null($id)
            ? Employee::where('id', $id)->get()
            : Employee::orderBy('name')->get();

        return view('employee.document.create-many-1', compact('employees', 'id'));
    }

    public function bondsDocumentsCreateMany(Request $request)
    {
        //check access permission
        if (!Gate::allows('bondDocument-store')) return response()->view('access.denied')->setStatusCode(401);

        $id = $request->bond_id ?? null;
        $bonds = !is_null($id)
            ? Bond::where('id', $id)->get()
            : Bond::with(['employee' => function ($q) {
                return $q->orderBy('name');
            }])->get();

        return view('bond.document.create-many-1', compact('bonds', 'id'));
    }

    public function employeesDocumentsStoreManyStep1(StoreEmployeeMultipleDocumentsRequest $request)
    {
        //check access permission
        if (!Gate::allows('employeeDocument-store')) return response()->view('access.denied')->setStatusCode(401);

        $documentTypes = DocumentType::orderBy('name')->get();

        $this->service->documentModel = new EmployeeDocument;

        try {
            $employeeDocuments = $this->service->createManyEmployeeDocumentsStep1($request->validated());
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Erro ao tentar obter arquivos: ' . $e->getMessage());
        }

        return view('employee.document.create-many-2', compact('employeeDocuments', 'documentTypes'));
    }

    public function bondsDocumentsStoreManyStep1(StoreBondMultipleDocumentsRequest $request)
    {
        //check access permission
        if (!Gate::allows('bondDocument-store')) return response()->view('access.denied')->setStatusCode(401);

        $documentTypes = DocumentType::orderBy('name')->get();

        $this->service->documentModel = new BondDocument;

        try {
            $bondDocuments = $this->service->createManyBondDocumentsStep1($request->validated());
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Erro ao tentar obter arquivos: ' . $e->getMessage());
        }

        return view('bond.document.create-many-2', compact('bondDocuments', 'documentTypes'));
    }

    public function employeesDocumentsStoreManyStep2(Request $request)
    {
        //check access permission
        if (!Gate::allows('employeeDocument-store')) return response()->view('access.denied')->setStatusCode(401);

        $this->service->documentModel = new EmployeeDocument;
        $this->service->createManyEmployeeDocumentsStep2($request->all());

        return redirect()->route('employeesDocuments.index')->with('success', 'Arquivos importados com sucesso.');
    }

    public function bondsDocumentsStoreManyStep2(Request $request)
    {
        //check access permission
        if (!Gate::allows('bondDocument-store')) return response()->view('access.denied')->setStatusCode(401);

        $this->service->documentModel = new BondDocument;
        $this->service->createManyBondDocumentsStep2($request->all());

        return redirect()->route('bondsDocuments.index')->with('success', 'Arquivos importados com sucesso.');
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\BondDocument $bondDocument
     * @return \Illuminate\Http\Response
     */
    public function showDocument($id)
    {
        $file = $this->service->getDocument($id);

        //check access permission
        if ($file->class === 'App\Models\EmployeeDocument' && !Gate::allows('employeeDocument-download')) return response()->view('access.denied')->setStatusCode(401);
        elseif ($file->class === 'App\Models\BondDocument' && !Gate::allows('bondDocument-download')) return response()->view('access.denied')->setStatusCode(401);

        return Response::make($file->data, 200, ['filename="' . $file->name . '"'])->header('Content-Type', $file->mime);
    }

    public function employeesDocumentsMassDownload(Employee $employee)
    {
        //check access permission
        if (!Gate::allows('employeeDocument-download')) return response()->view('access.denied')->setStatusCode(401);

        try {
            $zipFileName = $this->service->getAllDocumentsOfEmployee($employee);
        } catch (\Exception $e) {
            return redirect()->route('employees.show', $employee)->withErrors('Erro ao gerar o arquivo compactado: ' . $e->getMessage());
        }

        return response()->download($zipFileName)->deleteFileAfterSend(true);
    }

    public function bondsDocumentsMassDownload(Bond $bond)
    {
        //check access permission
        if (!Gate::allows('bondDocument-download')) return response()->view('access.denied')->setStatusCode(401);

        try {
            $zipFileName = $this->service->getAllDocumentsOfBond($bond);
        } catch (\Exception $e) {
            return redirect()->route('bonds.show', $bond)->withErrors('Erro ao gerar o arquivo compactado: ' . $e->getMessage());
        }

        return response()->download($zipFileName)->deleteFileAfterSend(true);
    }
}
