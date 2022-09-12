<?php

namespace App\Services;

use App\Events\EmployeeDocumentExported;
use App\Models\Document;
use App\Models\Employee;
use App\Models\EmployeeDocument;
use App\Services\Dto\StoreEmployeeDocumentDto;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class EmployeeDocumentService extends DocumentService
{
    public function __construct()
    {
        parent::__construct(EmployeeDocument::class, Employee::class);
    }

    /**
     * Undocumented function
     *
     * @param StoreEmployeeDocumentDto $storeEmployeeDocumentDto
     *
     * @return void
     */
    public function create(StoreEmployeeDocumentDto $storeEmployeeDocumentDto): void
    {
        DB::transaction(function () use ($storeEmployeeDocumentDto) {
            /**
             * @var UploadedFile $uploadedFile
             */
            $uploadedFile = $storeEmployeeDocumentDto->file;

            $originalName = $uploadedFile->getClientOriginalName();

            $fileData = $this->getFileData($uploadedFile);

            // Get old versions of this document type
            $oldDocuments = $this->getOldDocuments($storeEmployeeDocumentDto);

            // Delete old versions of this document type
            $this->deleteOldDocuments($oldDocuments);

            /** @var EmployeeDocument $documentable */
            $documentable = new EmployeeDocument([
                'employee_id' => $storeEmployeeDocumentDto->employeeId,
            ]);

            $documentable->save();

            /** @var Document $document */
            $document = new Document([
                'original_name' => $originalName,
                'document_type_id' => $storeEmployeeDocumentDto->documentTypeId,
                'documentable_type' => EmployeeDocument::class,
                'documentable_id' => $documentable->id,
                'file_data' => $fileData,
            ]);

            $documentable->document()->save($document);

            return $document;
        });
    }

    /**
     * Undocumented function
     *
     * @param Employee $employee
     * @param string $zipFileName
     *
     * @return string
     */
    public function exportDocuments(Employee $employee, ?string $zipFileName = null): string
    {
        /**
         * @var Collection<int, EmployeeDocument> $documentables
         */
        $documentables = $employee->employeeDocuments()->get(); // <= Particular line
        $zipFileName = $zipFileName ?? date('Y-m-d') . '_' . $employee->name . '.zip'; // <= Particular line

        EmployeeDocumentExported::dispatch($employee);

        return parent::exportGenericDocuments($documentables, $zipFileName);
    }

    /**
     * Undocumented function
     *
     * @param StoreEmployeeDocumentDto $storeEmployeeDocumentDto
     *
     * @return Collection<int, Document>
     */
    private function getOldDocuments(StoreEmployeeDocumentDto $storeEmployeeDocumentDto): Collection
    {
        $query = Document::query();

        $query = $query->where('document_type_id', $storeEmployeeDocumentDto->documentTypeId);
        $query = $query->whereHasMorph(
            'documentable',
            EmployeeDocument::class,
            static function (Builder $query) use ($storeEmployeeDocumentDto) {
                $query->where('employee_id', $storeEmployeeDocumentDto->employeeId);
            }
        );

        return $query->get();
    }
}
