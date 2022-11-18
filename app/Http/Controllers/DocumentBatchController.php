<?php

namespace App\Http\Controllers;

use App\Http\Requests\Document\batch\CreateDocumentBatchRequest;
use App\Http\Requests\Document\batch\Store2DocumentBatchRequest;
use App\Http\Requests\Document\batch\StoreDocumentBatchRequest;
use App\Models\Bond;
use App\Services\DocumentService;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class DocumentBatchController extends Controller
{
    public function __construct(private DocumentService $service)
    {
    }

    /**
     * @param CreateDocumentBatchRequest $request
     *
     * @return View
     */
    public function create(CreateDocumentBatchRequest $request): View
    {
        $id = $request->bond_id ?? null;
        $bonds = is_null($id)
            ? Bond::with(['employee' => static function ($q) {
                return $q->orderBy('name');
            },
            ])->get()
            : Bond::where('id', $id)->get();

        return view('document.create-many-1', compact('bonds', 'id'));
    }

    /**
     * @param StoreDocumentBatchRequest $request
     *
     * @return RedirectResponse|View
     */
    public function store(StoreDocumentBatchRequest $request): RedirectResponse|View
    {
        try {
            $bondDocuments = $this->service->createManyDocumentsStep1($request->validated());
        } catch (Exception $e) {
            return redirect()->back()->withErrors('Erro ao tentar obter arquivos: ' . $e->getMessage());
        }

        return view('document.create-many-2', compact('bondDocuments'));
    }

    /**
     * @param Store2DocumentBatchRequest $request
     *
     * @return RedirectResponse
     */
    public function store2(Store2DocumentBatchRequest $request): RedirectResponse
    {
        $this->service->createManyDocumentsStep2($request->all());

        return redirect()->route('documents.index')->with('success', 'Arquivos importados com sucesso.');
    }
}
