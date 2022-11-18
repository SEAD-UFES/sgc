<?php

namespace App\Services;

use App\Events\DocumentExported;
use App\Events\ModelListed;
use App\Events\ModelRead;
use App\Interfaces\DocumentRepositoryInterface;
use App\Models\Bond;
use App\Models\Document;
use App\Models\Employee;
use App\Services\Dto\DocumentDto;
use Exception;
use finfo;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\Facades\DB;

class DocumentService
{
    public function __construct(private DocumentRepositoryInterface $repository)
    {
    }

    /**
     * Undocumented function
     *
     * @param ?string $direction
     * @param ?string $sort
     *
     * @return LengthAwarePaginator<Document>
     */
    public function list(?string $direction, ?string $sort): LengthAwarePaginator
    {
        ModelListed::dispatch(Document::class);

        return $this->repository::all(direction: $direction, sort: $sort);
    }

    /**
     * Undocumented function
     *
     * @param DocumentDto $storeDocumentDto
     *
     * @return ?Document
     */
    public function create(DocumentDto $storeDocumentDto): ?Document
    {
        $document = null;

        // DB::transaction(function () use ($storeDocumentDto, &$document) {
        //     $oldDocuments = $this->repository
        //         ->getByBondIdOfTypeId(
        //             (int) $storeDocumentDto->relatedId,
        //             (int) $storeDocumentDto->documentTypeId
        //         );

        //     foreach ($oldDocuments as $oldDocument) {
        //         $this->repository->delete($oldDocument->id);
        //     }

        $document = $this->repository->createBondDocument($storeDocumentDto);
        // });

        /** @var Bond $bond */
        $bond = Bond::find($storeDocumentDto->relatedId);
        BondService::bondCheckRights($bond);

        return $document;
    }

    /**
     * Undocumented function
     *
     * @param Bond $bond
     * @param string $zipFileName
     *
     * @return string
     */
    public function zipDocuments(Bond $bond, ?string $zipFileName = null): string
    {
        /**
         * @var EloquentCollection<int, Document> $bondDocuments
         */
        $bondDocuments = $this->repository->getByBondId($bond->id);

        /**
         * @var Employee $employee
         */
        $employee = $bond->employee;
        $zipFileName = $zipFileName ?? date('Y-m-d') . '_' . $employee->name . '_' . $bond->id . '.zip';

        $zip = new \ZipArchive();

        if ($zip->open($zipFileName, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === true) {
            foreach ($bondDocuments as $document) {
                $documentName = $document->file_name;
                $documentData = base64_decode($document->file_data);
                $zip->addFromString($documentName, $documentData);
            }

            $zip->close();

            DocumentExported::dispatch($bond);

            return $zipFileName;
        }

        throw new Exception('failed: $zip->open()', 1);
    }

    /**
     * Undocumented function
     *
     * @param int $id
     *
     * @return SupportCollection<string, string>
     */
    public function assembleDocument(int $id): SupportCollection
    {
        /**
         * @var Document $document
         */
        $document = $this->repository::getById($id);

        ModelRead::dispatch($document);

        /**
         * @var string $documentName
         */
        $documentName = $document->file_name;

        /**
         * @var string $fileData
         */
        $fileData = base64_decode($document->file_data);

        /**
         * @var finfo $fileInfoResource
         */
        $fileInfoResource = finfo_open();

        /**
         * @var string $mimeType
         */
        $mimeType = finfo_buffer($fileInfoResource, $fileData, FILEINFO_MIME_TYPE);

        /**
         * @var SupportCollection<string, string> $file
         */
        $file = new SupportCollection();
        $file->put('name', $documentName);
        $file->put('mime', $mimeType);
        $file->put('data', $fileData);

        return $file;
    }

    /**
     * @param EloquentCollection<int, Document> $documentsCollection
     *
     * @return void
     */
    protected function deleteDocuments(EloquentCollection $documentsCollection): void
    {
        DB::transaction(function () use ($documentsCollection) {
            $documentsCollection->each(function ($document) {
                $this->repository::delete($document->id);
            });
        });
    }
}
