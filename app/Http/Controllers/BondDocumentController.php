<?php

namespace App\Http\Controllers;

use App\Models\Bond;
use App\Models\BondDocument;
use App\Models\DocumentType;
use Illuminate\Http\Request;
use App\Services\DocumentService;
use Illuminate\Support\Facades\Gate;
use App\CustomClasses\ModelFilterHelpers;
use App\Http\Requests\StoreBondDocumentRequest;
use App\Http\Requests\StoreBondMultipleDocumentsRequest;
use Illuminate\Http\Response;
use Illuminate\Contracts\Container\BindingResolutionException;
use InvalidArgumentException;

class BondDocumentController extends DocumentController
{
    /**
     * @param DocumentService $documentService
     * @return void
     */
    public function __construct(DocumentService $documentService)
    {
        parent::__construct($documentService);
        $this->service->documentClass = BondDocument::class;
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function bondsDocumentsIndex(Request $request)
    {
        //check access permission
        if (!Gate::allows('bondDocument-list')) {
            return response()->view('access.denied')->setStatusCode(403);
        }

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
        if (!Gate::allows('bondDocument-rights')) {
            return response()->view('access.denied')->setStatusCode(403);
        }

        $filters = ModelFilterHelpers::buildFilters($request, $this->service->documentClass::$accepted_filters);
        $documents = $this->service->listRights(sort: $request->query('sort'), direction: $request->query('direction'));

        return view('reports.rightsIndex', compact('documents', 'filters'))->with('i', (request()->input('page', 1) - 1) * 10);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function bondsDocumentsCreate()
    {
        //check access permission
        if (!Gate::allows('bondDocument-store')) {
            return response()->view('access.denied')->setStatusCode(403);
        }

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
    public function bondsDocumentsStore(StoreBondDocumentRequest $request)
    {
        //check access permission
        if (!Gate::allows('bondDocument-store')) {
            return response()->view('access.denied')->setStatusCode(403);
        }

        $this->service->create($request->validated());

        return redirect()->route('bondsDocuments.index')->with('success', 'Arquivo importado com sucesso.');
    }

    /**
     * @param Request $request
     * @return Response
     * @throws BindingResolutionException
     * @throws InvalidArgumentException
     */
    public function bondsDocumentsCreateMany(Request $request)
    {
        //check access permission
        if (!Gate::allows('bondDocument-store')) {
            return response()->view('access.denied')->setStatusCode(403);
        }

        $id = $request->bond_id ?? null;
        $bonds = !is_null($id)
            ? Bond::where('id', $id)->get()
            : Bond::with(['employee' => function ($q) {
                return $q->orderBy('name');
            }])->get();

        return view('bond.document.create-many-1', compact('bonds', 'id'));
    }

    public function bondsDocumentsStoreMany1(StoreBondMultipleDocumentsRequest $request)
    {
        //check access permission
        if (!Gate::allows('bondDocument-store')) {
            return response()->view('access.denied')->setStatusCode(403);
        }

        $documentTypes = DocumentType::orderBy('name')->get();

        try {
            $bondDocuments = $this->service->createManyDocumentsStep1($request->validated());
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Erro ao tentar obter arquivos: ' . $e->getMessage());
        }

        return view('bond.document.create-many-2', compact('bondDocuments', 'documentTypes'));
    }

    public function bondsDocumentsStoreMany2(Request $request)
    {
        //check access permission
        if (!Gate::allows('bondDocument-store')) {
            return response()->view('access.denied')->setStatusCode(403);
        }

        $this->service->createManyDocumentsStep2($request->all());

        return redirect()->route('bondsDocuments.index')->with('success', 'Arquivos importados com sucesso.');
    }

    public function bondsDocumentsExport(Bond $bond)
    {
        //check access permission
        if (!Gate::allows('bondDocument-download')) {
            return response()->view('access.denied')->setStatusCode(403);
        }

        try {
            $zipFileName = $this->service->exportBondDocuments($bond);
        } catch (\Exception $e) {
            return redirect()->route('bonds.show', $bond)->withErrors('Erro ao gerar o arquivo compactado: ' . $e->getMessage());
        }

        return response()->download($zipFileName)->deleteFileAfterSend(true);
    }
}