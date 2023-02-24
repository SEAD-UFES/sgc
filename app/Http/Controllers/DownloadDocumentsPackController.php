<?php

namespace App\Http\Controllers;

use App\Http\Requests\Document\ExportDocumentPackRequest;
use App\Interfaces\DocumentRepositoryInterface;
use App\Models\Bond;
use App\Models\Document;
use App\Models\Employee;
use App\Services\DocumentService;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DownloadDocumentsPackController extends Controller
{
    /**
     * @param DocumentService $service
     *
     * @return void
     */
    public function __construct(private DocumentService $service, private DocumentRepositoryInterface $repository)
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
    public function packBondEmployeeDocuments(ExportDocumentPackRequest $request, Bond $bond): BinaryFileResponse|RedirectResponse
    {
        try {
            $zipFileName = $this->service->zipDocuments($bond);
        } catch (Exception $e) {
            return redirect()->route('bonds.show', $bond)->withErrors('Erro ao gerar o arquivo compactado: ' . $e->getMessage());
        }

        return response()->download($zipFileName)->deleteFileAfterSend(true);
    }

    /**
     *
     * Creates a ZIP package of multiple employees' documents
     *
     * @param  ExportDocumentPackRequest  $request
     *
     * @return BinaryFileResponse|RedirectResponse
     */
    public function createEmployeeFilesZip(/* ExportDocumentPackRequest */Request $request): BinaryFileResponse|RedirectResponse
    {
        $attributes = $request->validate([
            'bonds.*' => 'sometimes',
        ]);

        if (empty($attributes['bonds'])) {
            return redirect()->route('courses.index')->withErrors('Nenhum colaborador selecionado');
        }

        // Create a new ZIP archive
        $zip = new \ZipArchive;
        $zipFileName = 'documentos_colaboradores.zip';

        if ($zip->open($zipFileName, \ZipArchive::CREATE) !== true) {
            return redirect()->route('courses.index')->withErrors('Erro interno: Não foi possível criar o arquivo compactado');
        }

        // Loop through each bond employee and add their files to a folder inside the ZIP archive
        foreach ($attributes['bonds'] as $bondId => $value) {
            /** @var Bond $bond */
            $bond = Bond::findOrFail($bondId);

            /** @var Employee $bondEmployee */
            $bondEmployee = $bond->employee;

            // Create a folder with the employee's name
            $folderName = $bondEmployee->name . '_' . $bondEmployee->cpf;
            $zip->addEmptyDir($folderName);

            /**
             * @var Collection<int, Document> $employeeFiles
             */
            // Get all the files associated with the bond employee
            $employeeFiles = $this->repository->getByBondId($bondId);

            // Add each file to the employee's folder inside the ZIP archive
            foreach ($employeeFiles as $file) {
                $fileName = $file->file_name;
                $fileContent = base64_decode($file->file_data);
                $zip->addFromString($folderName . '/' . $fileName, $fileContent);
            }
        }

        // Close the ZIP archive
        $zip->close();

        // Download the ZIP archive
        return response()->download($zipFileName)->deleteFileAfterSend(true);
    }
}
