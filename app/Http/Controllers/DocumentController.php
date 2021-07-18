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

class DocumentController extends Controller
{
    public function getViewParameters($model)
    {
        $documentTypes = DocumentType::orderBy('name')->get();

        $class = app("App\\Models\\$model");

        $documents = $class::all();

        SgcLogger::writeLog($model, 'index');

        return compact('documents', 'documentTypes');
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function employeesDocumentIndex()
    {
        $model = 'EmployeeDocument';

        $resArray = $this->getViewParameters($model);
        return view('employee.document.index', $resArray);
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function bondsDocumentIndex()
    {
        $model = 'BondDocument';

        $resArray = $this->getViewParameters($model);
        return view('bond.document.index', $resArray);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function employeesDocumentCreate($id = null)
    {
        $documentTypes = DocumentType::orderBy('name')->get();

        if (!is_null($id))
            $employees = Employee::where('id', $id)->get();
        else
            $employees = Employee::orderBy('name')->get();

        return view('employee.document.masscreate', compact('documentTypes', 'employees', 'id'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function bondsDocumentCreate()
    {
        $documentTypes = DocumentType::orderBy('name')->get();
        $bonds = Bond::all();
        return view('bond.document.create', compact('documentTypes', 'bonds'));
    }

    public function import(Request $request, $model)
    {
        $request->validate([
            'file' => 'required|mimes:pdf,jpeg,png,jpg|max:2048'
        ]);

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

            SgcLogger::writeLog($model, 'create');

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
        $request->validate([
            'file' => 'required|mimes:pdf,jpeg,png,jpg|max:2048'
        ]);

        $this->import($request, 'BondDocument');

        return redirect()->route('bonds.document.index')->with('success', 'Arquivo importado com sucesso.');
    }

    public function employeesDocumentMassImport(Request $request)
    {
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
                $documentTypes = DocumentType::orderBy('name')->get();
            }
        }

        return view('employee.document.massReview', compact('fileSet', 'documentTypes'));
    }

    public function employeesDocumentMassStore(Request $request)
    {
        $filesCount = $request->fileSetCount;
        $employeeId = $request->employeeId;

        for ($i = 0; $i < $filesCount; $i++)
        {
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
        
        SgcLogger::writeLog('Mass Employees Documents', 'create');

        return redirect()->route('employees.document.index')->with('success', 'Arquivos importados com sucesso.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BondDocument  $bondDocument
     * @return \Illuminate\Http\Response
     */
    public function show(BondDocument $bondDocument)
    {
        //
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
}
