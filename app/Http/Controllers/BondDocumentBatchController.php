<?php

namespace App\Http\Controllers;

use App\Http\Requests\BondDocument\batch\CreateBondDocumentBatchRequest;
use App\Http\Requests\BondDocument\batch\Store2BondDocumentBatchRequest;
use App\Http\Requests\BondDocument\batch\StoreBondDocumentBatchRequest;
use App\Models\Bond;
use App\Services\BondDocumentService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class BondDocumentBatchController extends Controller
{
    public function __construct(private BondDocumentService $service)
    {
    }

    /**
     * @param CreateBondDocumentBatchRequest $request
     *
     * @return View
     */
    public function create(CreateBondDocumentBatchRequest $request): View
    {
        $id = $request->bond_id ?? null;
        $bonds = is_null($id)
            ? Bond::with(['employee' => static function ($q) {
                return $q->orderBy('name');
            },
            ])->get()
            : Bond::where('id', $id)->get();

        return view('bond.document.create-many-1', compact('bonds', 'id'));
    }

    /**
     * @param StoreBondDocumentBatchRequest $request
     *
     * @return RedirectResponse|View
     */
    public function store(StoreBondDocumentBatchRequest $request): RedirectResponse|View
    {
        try {
            $bondDocuments = $this->service->createManyDocumentsStep1($request->validated());
        } catch (Exception $e) {
            return redirect()->back()->withErrors('Erro ao tentar obter arquivos: ' . $e->getMessage());
        }

        return view('bond.document.create-many-2', compact('bondDocuments'));
    }

    /**
     * @param Store2BondDocumentBatchRequest $request
     *
     * @return RedirectResponse
     */
    public function store2(Store2BondDocumentBatchRequest $request): RedirectResponse
    {
        $this->service->createManyDocumentsStep2($request->all());

        return redirect()->route('bonds_documents.index')->with('success', 'Arquivos importados com sucesso.');
    }
}
