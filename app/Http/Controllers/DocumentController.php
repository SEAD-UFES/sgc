<?php

namespace App\Http\Controllers;

use App\Models\Bond;
use App\Models\Employee;
use App\Models\BondDocument;
use App\Models\DocumentType;
use Illuminate\Http\Request;
use App\Helpers\RequestHelper;
use App\CustomClasses\SgcLogger;
use App\Models\EmployeeDocument;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use App\CustomClasses\ModelFilterHelpers;
use Illuminate\Database\Eloquent\Collection;

class DocumentController extends Controller
{
    public function getViewParameters($model, $request)
    {
        $documentTypes = DocumentType::orderBy('name')->get();

        //get class
        $class = app("App\\Models\\$model");
        $documentsQuery = $class;

        //filters
        $filters = ModelFilterHelpers::buildFilters($request, $class::$accepted_filters);
        $documentsQuery = $documentsQuery->AcceptRequest($class::$accepted_filters)->filter();

        //sort
        $documentsQuery = $documentsQuery->sortable(['updated_at' => 'desc']);

        //get paginate and add querystring on paginate links
        $documents = $documentsQuery->paginate(10);
        $documents->withQueryString();

        SgcLogger::writeLog(target: $model, action: 'index');

        return compact('documents', 'documentTypes', 'filters');
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function employeesDocumentsIndex(Request $request)
    {
        //check access permission
        if (!Gate::allows('employeeDocument-list')) return response()->view('access.denied')->setStatusCode(401);

        $model = 'EmployeeDocument';
        $resArray = $this->getViewParameters($model, $request);
        return view('employee.document.index', $resArray);
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function bondsDocumentsIndex(Request $request)
    {
        //check access permission
        if (!Gate::allows('bondDocument-list')) return response()->view('access.denied')->setStatusCode(401);

        $model = 'BondDocument';
        $resArray = $this->getViewParameters($model, $request);
        return view('bond.document.index', $resArray);
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

        $type = DocumentType::where('name', 'Ficha de Inscrição - Termos e Licença')->first();

        $documentsQuery = BondDocument::with('bond')
            ->where('bond_documents.document_type_id', $type->id)
            ->whereHas('bond', function ($query) {
                $query->whereNotNull('uaba_checked_at')->where('impediment', false);
            });

        //filters
        $filters = ModelFilterHelpers::buildFilters($request, BondDocument::$accepted_filters);
        $documentsQuery = $documentsQuery->AcceptRequest(BondDocument::$accepted_filters)->filter();

        //sort
        $documentsQuery = $documentsQuery->sortable(['updated_at' => 'desc']);

        //get paginate and add querystring on paginate links
        $documents = $documentsQuery->paginate(10);
        $documents->withQueryString();

        //write on log
        SgcLogger::writeLog(target: 'BondsRightsIndex', action: 'index');

        return view('reports.rightsIndex', compact('documents', 'filters'))->with('i', (request()->input('page', 1) - 1) * 10);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function employeesDocumentsCreate(Request $request)
    {
        //check access permission
        if (!Gate::allows('employeeDocument-store')) return response()->view('access.denied')->setStatusCode(401);

        $documentTypes = DocumentType::orderBy('name')->get();
        $employees = Employee::all();

        //write on log
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

        //write on log
        SgcLogger::writeLog(target: 'bondsDocument', action: 'create');

        return view('bond.document.create', compact('documentTypes', 'bonds'));
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

        //write on log
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

        //write on log
        SgcLogger::writeLog(target: 'bondsDocument', action: 'create');

        return view('bond.document.create-many-1', compact('bonds', 'id'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function employeesDocumentsStore(Request $request)
    {
        //validation
        $request->validate([
            'file' => 'required|mimes:pdf,jpeg,png,jpg|max:2048'
        ]);

        //check access permission
        if (!Gate::allows('employeeDocument-store')) return response()->view('access.denied')->setStatusCode(401);

        //save the model
        $employeeDocument = new EmployeeDocument();
        $employeeDocument->employee_id = $request->employee_id;
        $employeeDocument->document_type_id = $request->document_type_id;
        $employeeDocument->original_name = $request->file() ? $request->file->getClientOriginalName() : null;
        $employeeDocument->file_data = $request->file() ? RequestHelper::getFileDataFromRequest($request, 'file') : null;
        $employeeDocument->save();

        //write on log
        SgcLogger::writeLog(target: 'employeeDocument', action: 'store');

        return redirect()->route('employeesDocuments.index')->with('success', 'Arquivo importado com sucesso.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function bondsDocumentsStore(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:pdf,jpeg,png,jpg|max:2048'
        ]);

        //check access permission
        if (!Gate::allows('bondDocument-store')) return response()->view('access.denied')->setStatusCode(401);

        //save the model
        $bondDocument = new BondDocument();
        $bondDocument->bond_id = $request->bonds;
        $bondDocument->document_type_id = $request->documentTypes;
        $bondDocument->original_name = $request->file() ? $request->file->getClientOriginalName() : null;
        $bondDocument->file_data = $request->file() ? RequestHelper::getFileDataFromRequest($request, 'file') : null;
        $bondDocument->save();

        SgcLogger::writeLog(target: 'bondDocument', action: 'store');

        return redirect()->route('bondsDocuments.index')->with('success', 'Arquivo importado com sucesso.');
    }

    public function employeesDocumentsStoreManyStep1(Request $request)
    {
        $request->validate([
            'files.*' => 'required|mimes:pdf,jpeg,png,jpg|max:2048',
        ]);

        //check access permission
        if (!Gate::allows('employeeDocument-store')) return response()->view('access.denied')->setStatusCode(401);

        if ($request->hasfile('files')) {
            $files = $request->file('files');
            $employeeDocuments = collect();
            foreach ($files as $file) {
                $tmpFileName = time() . '.' . $file->getClientOriginalName();
                $tmpFilePath = $file->storeAs('temp', $tmpFileName, 'local');

                $employeeDocument = new EmployeeDocument();
                $employeeDocument->employee_id = $request->employees;
                $employeeDocument->original_name = $file->getClientOriginalName();
                $employeeDocument->filePath = $tmpFilePath;

                $employeeDocuments->push($employeeDocument);
            }
        }

        $documentTypes = DocumentType::orderBy('name')->get();

        SgcLogger::writeLog(target: 'employeeDocument', action: 'store');

        return view('employee.document.create-many-2', compact('employeeDocuments', 'documentTypes'));
    }

    public function bondsDocumentsStoreManyStep1(Request $request)
    {
        //validation
        $request->validate([
            'files.*' => 'required|mimes:pdf,jpeg,png,jpg|max:2048',
        ]);

        //check access permission
        if (!Gate::allows('bondDocument-store')) return response()->view('access.denied')->setStatusCode(401);

        //set files data.
        if ($request->hasfile('files')) {
            $files = $request->file('files');
            $bondDocuments = collect();
            foreach ($files as $file) {
                //create tmp file. (deleted on step02)
                $tmpFileName = time() . '.' . $file->getClientOriginalName();
                $tmpFilePath = $file->storeAs('temp', $tmpFileName, 'local');

                //build model (with no document_type_id)
                $bondDocument = new BondDocument();
                $bondDocument->bond_id = $request->bond_id;
                $bondDocument->original_name = $file->getClientOriginalName();
                $bondDocument->tmp_file_path = $tmpFilePath;

                //push on list
                $bondDocuments->push($bondDocument);
            }
        }

        //get documentTypes
        $documentTypes = DocumentType::orderBy('name')->get();

        //log
        SgcLogger::writeLog(target: 'bondDocument', action: 'store');

        return view('bond.document.create-many-2', compact('bondDocuments', 'documentTypes'));
    }

    public function employeesDocumentsStoreManyStep2(Request $request)
    {
        //check access permission
        if (!Gate::allows('employeeDocument-store')) return response()->view('access.denied')->setStatusCode(401);

        $employeeDocumentsCount = $request->fileSetCount;

        DB::transaction(function () use ($request, $employeeDocumentsCount) {

            for ($i = 0; $i < $employeeDocumentsCount; $i++) {
                $filePath = $request->input('filePath_' . $i);

                $employeeDocument = new EmployeeDocument();
                $employeeDocument->employee_id = $request->employeeId;
                $employeeDocument->document_type_id = $request->input('documentTypes_' . $i);
                $employeeDocument->original_name = $request->input('fileName_' . $i);
                $employeeDocument->file_data = RequestHelper::getFileDataFromFilePath($filePath);

                $oldDocuments = new EmployeeDocument();
                $oldDocuments = $oldDocuments
                    ->where('employee_id', $employeeDocument->employee_id)
                    ->where('document_type_id', $employeeDocument->document_type_id)
                    ->get();
                foreach ($oldDocuments as $old) $old->delete();

                $employeeDocument->save();
            }
        });

        //delete tmp_files
        for ($i = 0; $i < $employeeDocumentsCount; $i++) {
            $filePath = $request->input('filePath_' . $i);
            Storage::delete($filePath);
        }

        SgcLogger::writeLog(target: 'Mass Employees Documents', action: 'create');

        return redirect()->route('employeesDocuments.index')->with('success', 'Arquivos importados com sucesso.');
    }

    public function bondsDocumentsStoreManyStep2(Request $request)
    {
        //check access permission
        if (!Gate::allows('bondDocument-store')) return response()->view('access.denied')->setStatusCode(401);

        //number of files
        $bondDocumentsCount = $request->bondDocumentsCount;

        //save all documents
        DB::transaction(function () use ($request, $bondDocumentsCount) {
            //save model for each file
            for ($i = 0; $i < $bondDocumentsCount; $i++) {
                //get file_path
                $filePath = $request->input('filePath_' . $i);

                //set the model
                $bondDocument = new BondDocument();
                $bondDocument->bond_id = $request->bond_id;
                $bondDocument->document_type_id = $request->input('documentTypes_' . $i);
                $bondDocument->original_name = $request->input('fileName_' . $i);
                $bondDocument->file_data = RequestHelper::getFileDataFromFilePath($filePath);

                //delete old same type document
                $oldDocuments = new BondDocument();
                $oldDocuments = $oldDocuments
                    ->where('bond_id', $bondDocument->bond_id)
                    ->where('document_type_id', $bondDocument->document_type_id)
                    ->get();
                foreach ($oldDocuments as $old) $old->delete();

                //save new BondDocument
                $bondDocument->save();
            }
        });

        //delete tmp_files
        for ($i = 0; $i < $bondDocumentsCount; $i++) {
            $filePath = $request->input('filePath_' . $i);
            Storage::delete($filePath);
        }

        //log
        SgcLogger::writeLog(target: 'Create many BondDocuments', action: 'create');

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

        $class = app("App\\Models\\$model");

        $document = $class::find($id);

        $file = base64_decode($document->file_data);

        $f = finfo_open();

        $mimeType = finfo_buffer($f, $file, FILEINFO_MIME_TYPE);

        SgcLogger::writeLog(target: $model, action: 'show');

        return Response::make($file, 200, [
            'filename="' . $document->original_name . '"'
        ])->header('Content-Type', $mimeType);
    }

    public function employeesDocumentsMassDownload(Employee $employee)
    {
        //check access permission
        if (!Gate::allows('employeeDocument-download')) return response()->view('access.denied')->setStatusCode(401);

        $documents = $employee->employeeDocuments;

        $zipFileName = date('Y-m-d') . '_' . $employee->name . '.zip';

        $zip = new \ZipArchive();

        if ($zip->open($zipFileName, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === TRUE) {

            foreach ($documents as $document) $zip->addFromString($document->original_name, base64_decode($document->file_data));
            $zip->close();

            SgcLogger::writeLog(target: $employee, action: 'employeeDocuments download');

            return response()->download($zipFileName)->deleteFileAfterSend(true);
        } else {
            echo 'failed: $zip->open()';
        }
    }

    public function bondsDocumentsMassDownload(Bond $bond)
    {
        //check access permission
        if (!Gate::allows('bondDocument-download')) return response()->view('access.denied')->setStatusCode(401);

        $documents = $bond->bondDocuments;

        $zipFileName = date('Y-m-d') . '_' . $bond->employee->name . '_' . $bond->id . '.zip';

        $zip = new \ZipArchive();

        if ($zip->open($zipFileName, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === TRUE) {

            foreach ($documents as $document) $zip->addFromString($document->original_name, base64_decode($document->file_data));
            $zip->close();

            SgcLogger::writeLog(target: $bond, action: 'bondDocuments download');

            return response()->download($zipFileName)->deleteFileAfterSend(true);
        } else {
            echo 'failed: $zip->open()';
        }
    }
}
