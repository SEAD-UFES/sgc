<?php

namespace App\Http\Controllers;

use App\Models\Bond;
use App\Models\Employee;
use App\Models\BondDocument;
use App\Models\DocumentType;
use Illuminate\Http\Request;
use App\CustomClasses\SgcLogger;
use App\Models\EmployeeDocument;
use App\Services\DocumentService;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Response;
use App\CustomClasses\ModelFilterHelpers;
use App\Http\Requests\DocumentStoreRequest;

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

        //filters
        $filters = ModelFilterHelpers::buildFilters($request, EmployeeDocument::$accepted_filters);

        $this->service->documentModel = new EmployeeDocument;

        $documents = $this->service->list();

        return view('employee.document.index', compact('documents', 'filters'));
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function bondsDocumentsIndex(Request $request)
    {
        //check access permission
        if (!Gate::allows('bondDocument-list')) return response()->view('access.denied')->setStatusCode(401);

        //filters
        $filters = ModelFilterHelpers::buildFilters($request, BondDocument::$accepted_filters);

        $this->service->documentModel = new BondDocument;

        $documents = $this->service->list();

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

        $documents = $this->service->listRights();

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

        SgcLogger::writeLog(target: 'employeeDocument', action: 'create');

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

        SgcLogger::writeLog(target: 'bondsDocument', action: 'create');

        return view('bond.document.create', compact('documentTypes', 'bonds'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function employeesDocumentsStore(DocumentStoreRequest $request)
    {
        //check access permission
        if (!Gate::allows('employeeDocument-store')) return response()->view('access.denied')->setStatusCode(401);

        $this->service->documentModel = new EmployeeDocument;
        $this->service->createEmployeeDocument($request->all());

        return redirect()->route('employeesDocuments.index')->with('success', 'Arquivo importado com sucesso.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function bondsDocumentsStore(DocumentStoreRequest $request)
    {
        //check access permission
        if (!Gate::allows('bondDocument-store')) return response()->view('access.denied')->setStatusCode(401);

        $this->service->documentModel = new BondDocument;
        $this->service->createBondDocument($request->all());

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

        SgcLogger::writeLog(target: 'employeesDocument', action: 'create');

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

        SgcLogger::writeLog(target: 'bondsDocument', action: 'create');

        return view('bond.document.create-many-1', compact('bonds', 'id'));
    }

    public function employeesDocumentsStoreManyStep1(DocumentStoreRequest $request)
    {
        //check access permission
        if (!Gate::allows('employeeDocument-store')) return response()->view('access.denied')->setStatusCode(401);

        $documentTypes = DocumentType::orderBy('name')->get();

        $this->service->documentModel = new EmployeeDocument;
        $employeeDocuments = $this->service->createManyEmployeeDocumentsStep1($request->all());

        return view('employee.document.create-many-2', compact('employeeDocuments', 'documentTypes'));
    }

    public function bondsDocumentsStoreManyStep1(DocumentStoreRequest $request)
    {
        //check access permission
        if (!Gate::allows('bondDocument-store')) return response()->view('access.denied')->setStatusCode(401);

        $documentTypes = DocumentType::orderBy('name')->get();

        $this->service->documentModel = new BondDocument;
        $bondDocuments = $this->service->createManyBondDocumentsStep1($request->all());

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
     * @param  \App\Models\BondDocument  $bondDocument
     * @return \Illuminate\Http\Response
     */
    public function showDocument($id, $model)
    {
        //check access permission
        if ($model === 'EmployeeDocument' && !Gate::allows('employeeDocument-download')) return response()->view('access.denied')->setStatusCode(401);
        elseif ($model === 'BondDocument' && !Gate::allows('bondDocument-download')) return response()->view('access.denied')->setStatusCode(401);

        $this->service->documentModel = app("App\\Models\\$model");
        $file = $this->service->getDocument($id);

        return Response::make($file->data, 200, ['filename="' . $file->name . '"'])->header('Content-Type', $file->mime);
    }

    public function employeesDocumentsMassDownload(Employee $employee)
    {
        //check access permission
        if (!Gate::allows('employeeDocument-download')) return response()->view('access.denied')->setStatusCode(401);

        try {
            $zipFileName = $this->service->getAllDocumentsOfEmployee($employee);
        } catch (\Throwable $th) {
            //throw $th;
        }

        return response()->download($zipFileName)->deleteFileAfterSend(true);
    }

    public function bondsDocumentsMassDownload(Bond $bond)
    {
        //check access permission
        if (!Gate::allows('bondDocument-download')) return response()->view('access.denied')->setStatusCode(401);

        try {
            $zipFileName = $this->service->getAllDocumentsOfBond($bond);
        } catch (\Throwable $th) {
            //throw $th;
        }

        return response()->download($zipFileName)->deleteFileAfterSend(true);
    }
}
