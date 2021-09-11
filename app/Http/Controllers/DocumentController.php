<?php

namespace App\Http\Controllers;

use App\Models\EmployeeDocument;
use App\Models\BondDocument;
use App\Models\DocumentType;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\CustomClasses\SgcLogger;
use App\Models\Employee;
use App\Models\Bond;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Response;
use App\CustomClasses\ModelFilterHelpers;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;

class DocumentController extends Controller
{
    public function getViewParameters($model, $request)
    {
        $documentTypes = DocumentType::orderBy('name')->get();

        //get class
        $class = app("App\\Models\\$model");
        $documents_query = $class;

        //filters
        $filters = ModelFilterHelpers::buildFilters($request, $class::$accepted_filters);
        $documents_query = $documents_query->AcceptRequest($class::$accepted_filters)->filter();

        //sort
        $documents_query = $documents_query->sortable(['updated_at' => 'desc']);

        //get paginate and add querystring on paginate links
        $documents = $documents_query->paginate(10);
        $documents->withQueryString();

        SgcLogger::writeLog(target: $model, action: 'index');

        return compact('documents', 'documentTypes', 'filters');
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function employeesDocumentIndex(Request $request)
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
    public function bondsDocumentIndex(Request $request)
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

        $documents_query = BondDocument::with('bond')
            ->where('bond_documents.document_type_id', $type->id)
            ->whereHas('bond', function ($query) {
                $query->whereNotNull('uaba_checked_at')->where('impediment', false);
            });

        //filters
        $filters = ModelFilterHelpers::buildFilters($request, BondDocument::$accepted_filters);
        $documents_query = $documents_query->AcceptRequest(BondDocument::$accepted_filters)->filter();

        //sort
        $documents_query = $documents_query->sortable(['updated_at' => 'desc']);

        //get paginate and add querystring on paginate links
        $documents = $documents_query->paginate(10);
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
    public function employeeDocumentCreateMany(Request $request)
    {
        //check access permission
        if (!Gate::allows('employeeDocument-store')) return response()->view('access.denied')->setStatusCode(401);

        $documentTypes = DocumentType::orderBy('name')->get();

        $id = $request->id ?? null;
        $employees = !is_null($id)
            ? Employee::where('id', $id)->get()
            : Employee::orderBy('name')->get();

        //write on log
        SgcLogger::writeLog(target: 'employeesDocument', action: 'create');

        return view('employee.document.create-many-1', compact('documentTypes', 'employees', 'id'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function bondsDocumentCreate()
    {
        //check access permission
        if (!Gate::allows('bondDocument-store')) return response()->view('access.denied')->setStatusCode(401);

        $documentTypes = DocumentType::orderBy('name')->get();
        $bonds = Bond::all();

        //write on log
        SgcLogger::writeLog(target: 'bondsDocument', action: 'create');

        return view('bond.document.create', compact('documentTypes', 'bonds'));
    }

    public function import(Request $request, $model)
    {
        $request->validate([
            'file' => 'required|mimes:pdf,jpeg,png,jpg|max:2048'
        ]);

        //check access permission
        if ($model === 'EmployeeDocument' && !Gate::allows('employeeDocument-store')) return response()->view('access.denied')->setStatusCode(401);
        elseif ($model === 'BondDocument' && !Gate::allows('bondDocument-store')) return response()->view('access.denied')->setStatusCode(401);

        if ($request->file()) {
            $fileName = time() . '.' . $request->file->getClientOriginalName();
            $filePath = $request->file('file')->storeAs('temp', $fileName, 'local');

            $doc = file_get_contents(base_path('storage/app/' . $filePath), true);

            $base64 = base64_encode($doc);

            $class = app("App\\Models\\$model");

            $document = new $class();

            if ($request->has('employees'))
                $document->employee_id = $request->employees;

            if ($request->has('bonds'))
                $document->bond_id = $request->bonds;


            $document->original_name = $request->file->getClientOriginalName();
            $document->document_type_id = $request->documentTypes;
            $document->file_data = $base64;

            $document->save();

            SgcLogger::writeLog(target: $model, action: 'create');

            Storage::delete($filePath);
        }

        //return redirect()->route('documents.index')->with('success', 'Arquivo importado com sucesso.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function bondsDocumentStore(Request $request)
    {
        //check access permission
        if (!Gate::allows('bondDocument-store')) return response()->view('access.denied')->setStatusCode(401);

        $request->validate([
            'file' => 'required|mimes:pdf,jpeg,png,jpg|max:2048'
        ]);

        $this->import($request, 'BondDocument');

        SgcLogger::writeLog(target: 'bondDocument', action: 'store');

        return redirect()->route('bonds.document.index')->with('success', 'Arquivo importado com sucesso.');
    }

    public function employeeDocumentStoreManyFase1(Request $request)
    {
        //check access permission
        if (!Gate::allows('employeeDocument-store')) return response()->view('access.denied')->setStatusCode(401);

        $request->validate([
            'files.*' => 'required|mimes:pdf,jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasfile('files')) {
            $files = $request->file('files');

            $fileSet = collect();

            foreach ($files as $file) {
                $fileName = time() . '.' . $file->getClientOriginalName();
                $filePath = $file->storeAs('temp', $fileName, 'local');

                $document = new EmployeeDocument();

                $document->employee_id = $request->employees;

                $document->original_name = $file->getClientOriginalName();

                $document->filePath = $filePath;

                $fileSet->push($document);

                SgcLogger::writeLog(target: 'employeeDocument', action: 'store');

                $documentTypes = DocumentType::orderBy('name')->get();
            }
        }

        return view('employee.document.create-many-2', compact('fileSet', 'documentTypes'));
    }

    public function employeeDocumentStoreManyFase2(Request $request)
    {
        //check access permission
        if (!Gate::allows('employeeDocument-store')) return response()->view('access.denied')->setStatusCode(401);

        DB::transaction(function () use ($request) {
            $filesCount = $request->fileSetCount;
            $employeeId = $request->employeeId;

            for ($i = 0; $i < $filesCount; $i++) {
                $document = new EmployeeDocument();

                $document->employee_id = $employeeId;
                $document->document_type_id = $request->input('documentTypes_' . $i);

                $oldDocuments = EmployeeDocument::where('document_type_id', $document->document_type_id)->where('employee_id', $document->employee_id)->get();
                foreach ($oldDocuments as $old) $old->delete();

                $document->original_name = $request->input('fileName_' . $i);

                $filePath = $request->input('filePath_' . $i);

                $doc = file_get_contents(base_path('storage/app/' . $filePath), true);
                $base64 = base64_encode($doc);
                $document->file_data = $base64;

                $document->save();

                Storage::delete($filePath);
            }

            SgcLogger::writeLog(target: 'Mass Employees Documents', action: 'create');
        });

        return redirect()->route('employees.document.index')->with('success', 'Arquivos importados com sucesso.');
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

        $mime_type = finfo_buffer($f, $file, FILEINFO_MIME_TYPE);

        SgcLogger::writeLog(target: $model, action: 'show');

        return Response::make($file, 200, [
            'filename="' . $document->original_name . '"'
        ])->header('Content-Type', $mime_type);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BondDocument  $bondDocument
     * @return \Illuminate\Http\Response
     */
    public function edit(BondDocument $bondDocument)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BondDocument  $bondDocument
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BondDocument $bondDocument)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BondDocument  $bondDocument
     * @return \Illuminate\Http\Response
     */
    public function destroy(BondDocument $bondDocument)
    {
        //
    }

    public function bondDocumentsMassDownload(Bond $bond)
    {
        //check access permission
        if (!Gate::allows('bondDocument-download')) return response()->view('access.denied')->setStatusCode(401);

        $documents = $bond->bondDocuments;

        $zipFileName = date('Y-m-d') . '_' . $bond->employee->name . '_' . $bond->id;
        $zipFile = $zipFileName . '.zip';

        $zip = new \ZipArchive();

        if ($zip->open($zipFile, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === TRUE) {

            foreach ($documents as $document)
                $zip->addFromString($document->original_name, base64_decode($document->file_data));

            $zip->close();

            SgcLogger::writeLog(target: $bond, action: 'bondDocuments download');

            return response()->download($zipFile)->deleteFileAfterSend(true);
        } else {
            echo 'failed: $zip->open()';
        }
    }
}
