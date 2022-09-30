<?php

namespace App\Http\Controllers;

use App\Http\Requests\BondDocument\ExportBondDocumentPackRequest;
use App\Models\Bond;
use App\Services\BondDocumentService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DownloadBondDocumentsPackController extends Controller
{
    /**
     * @param BondDocumentService $service
     *
     * @return void
     */
    public function __construct(private BondDocumentService $service)
    {
    }

    /**
     * Handle the incoming request.
     *
     * @param  ExportBondDocumentPackRequest  $request
     * @param  Bond  $bond
     *
     * @return BinaryFileResponse|RedirectResponse
     */
    public function __invoke(ExportBondDocumentPackRequest $request, Bond $bond): BinaryFileResponse|RedirectResponse
    {
        try {
            $zipFileName = $this->service->zipDocuments($bond);
        } catch (Exception $e) {
            return redirect()->route('bonds.show', $bond)->withErrors('Erro ao gerar o arquivo compactado: ' . $e->getMessage());
        }

        return response()->download($zipFileName)->deleteFileAfterSend(true);
    }
}
