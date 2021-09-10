<?php

namespace App\Http\Controllers;

use App\Models\EmployeeDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\DocumentType;
use App\Models\Employee;
use App\CustomClasses\SgcLogger;
use App\Helpers\RequestHelper;

class EmployeeDocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //check access permission
        if (!Gate::allows('employeeDocument-store')) return response()->view('access.denied')->setStatusCode(401);

        $employees = Employee::all();
        $documentTypes = DocumentType::orderBy('name')->get();

        //write on log
        SgcLogger::writeLog(target: 'employeeDocument', action: 'create');

        return view('employee.document.create', compact('documentTypes', 'employees'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
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

        return redirect()->route('employees.document.index')->with('success', 'Arquivo importado com sucesso.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\EmployeeDocument  $employeeDocument
     * @return \Illuminate\Http\Response
     */
    public function show(EmployeeDocument $employeeDocument)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\EmployeeDocument  $employeeDocument
     * @return \Illuminate\Http\Response
     */
    public function edit(EmployeeDocument $employeeDocument)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\EmployeeDocument  $employeeDocument
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EmployeeDocument $employeeDocument)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EmployeeDocument  $employeeDocument
     * @return \Illuminate\Http\Response
     */
    public function destroy(EmployeeDocument $employeeDocument)
    {
        //
    }
}
