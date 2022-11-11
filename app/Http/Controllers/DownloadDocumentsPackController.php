<?php

namespace App\Http\Controllers;

use App\Http\Requests\Document\ExportDocumentPackRequest;
use App\Models\Bond;
use App\Services\DocumentService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DownloadDocumentsPackController extends Controller
{
    /**
     * @param DocumentService $service
     *
     * @return void
     */
    public function __construct(private DocumentService $service)
    {
    }

    /**
     * Handle the incoming request.
     *
     * @param  ExportDocumentPackRequest  $request
     * @param  Bond  $bond
     *
     * @return BinaryFileResponse|RedirectResponse
     */
    public function __invoke(ExportDocumentPackRequest $request, Bond $bond): BinaryFileResponse|RedirectResponse
    {
        try {
            $zipFileName = $this->service->zipDocuments($bond);
        } catch (Exception $e) {
            return redirect()->route('bonds.show', $bond)->withErrors('Erro ao gerar o arquivo compactado: ' . $e->getMessage());
        }

        return response()->download($zipFileName)->deleteFileAfterSend(true);
    }
}
