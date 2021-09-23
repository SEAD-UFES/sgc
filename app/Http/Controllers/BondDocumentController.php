<?php

namespace App\Http\Controllers;

use App\Models\BondDocument;
use App\Models\Bond;
use App\Models\DocumentType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\CustomClasses\SgcLogger;
use App\Helpers\RequestHelper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BondDocumentController extends Controller
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
    public function create()
    {
        //
    }

    public function createMany(Request $request)
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
        SgcLogger::writeLog(target: 'employeesDocument', action: 'create');

        return view('bond.document.create-many-1', compact('bonds', 'id'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    public function storeManyStep1(Request $request)
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
                //create tmp file. (deleted on fase02)
                $tmp_file_name = time() . '.' . $file->getClientOriginalName();
                $tmp_file_path = $file->storeAs('temp', $tmp_file_name, 'local');

                //build model (with no document_type_id)
                $bondDocument = new BondDocument();
                $bondDocument->bond_id = $request->bond_id;
                $bondDocument->original_name = $file->getClientOriginalName();
                $bondDocument->tmp_file_path = $tmp_file_path;

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

    public function storeManyStep2(Request $request)
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
                $file_path = $request->input('filePath_' . $i);

                //set the model
                $bondDocument = new BondDocument();
                $bondDocument->bond_id = $request->bond_id;
                $bondDocument->document_type_id = $request->input('documentTypes_' . $i);
                $bondDocument->original_name = $request->input('fileName_' . $i);
                $bondDocument->file_data = RequestHelper::getFileDataFromFilePath($file_path);

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
            $file_path = $request->input('filePath_' . $i);
            Storage::delete($file_path);
        }

        //log
        SgcLogger::writeLog(target: 'Create many BondDocuments', action: 'create');

        return redirect()->route('bonds.document.index')->with('success', 'Arquivos importados com sucesso.');
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
