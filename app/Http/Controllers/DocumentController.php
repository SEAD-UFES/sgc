<?php

namespace App\Http\Controllers;

use App\Models\EmployeeDocument;
use App\Models\BondDocument;
use App\Models\DocumentType;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\CustomClasses\SgcLogger;
use Illuminate\Database\Eloquent\Collection;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $documentTypes = DocumentType::orderBy('name')->get();

        $documentsR = collect(EmployeeDocument::all());
        $documentsB = collect(BondDocument::all());

        $documents = collect();

        foreach ($documentsR as $doc)
            $documents->push($doc);

        foreach ($documentsB as $doc)
            $documents->push($doc);

        SgcLogger::writeLog('Documents');

        return view('document.index', compact('documents', 'documentTypes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $documentTypes = DocumentType::orderBy('name')->get();
        return view('document.create', compact('documentTypes'));
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

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:pdf,jpeg,png,jpg|max:2048'
        ]);

        if ($request->file()) {
            $fileName = time() . '.' . $request->file->getClientOriginalName();
            $filePath = $request->file('file')->storeAs('temp', $fileName, 'local');
            
            $doc = file_get_contents(base_path('storage/app/'.$filePath), true);
            
            $base64 = base64_encode($doc);

            //dd($request->documentTypes);

            if ($request->documentTypes == '1')
                $document = new EmployeeDocument();
            else
                $document = new BondDocument();


            $document->original_name = $request->file->getClientOriginalName();
            $document->document_type_id = $request->documentTypes;
            $document->file_data = $base64;

            $document->save();

            Storage::delete($filePath); //base_path('storage\app\\'.$filePath));
        }

        return redirect()->route('documents.index')->with('success', 'Arquivo importado com sucesso.');
    }
}
