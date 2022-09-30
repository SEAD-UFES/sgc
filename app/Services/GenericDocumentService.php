<?php

namespace App\Services;

use App\Events\ModelRead;
use App\Models\Document;
use App\Repositories\GenericDocumentRepository;
use Exception;
use finfo;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\Facades\DB;

class GenericDocumentService
{
    private GenericDocumentRepository $genericRepository;

    public function __construct()
    {
        $this->genericRepository = new GenericDocumentRepository();
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
        $document = $this->genericRepository::getById($id);

        ModelRead::dispatch($document);

        /**
         * @var string $documentName
         */
        $documentName = $document->original_name;

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
     * @param EloquentCollection<int, Document> $documents
     * @param string $zipFileName
     *
     * @return string
     *
     * @throws Exception
     */
    protected function zipGenericDocuments(EloquentCollection $documents, string $zipFileName): string
    {
        $zip = new \ZipArchive();

        if ($zip->open($zipFileName, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === true) {
            foreach ($documents as $document) {
                $documentName = $document->original_name;
                $documentData = base64_decode($document->file_data);
                $zip->addFromString($documentName, $documentData);
            }

            $zip->close();

            return $zipFileName;
        }

        throw new Exception('failed: $zip->open()', 1);
    }

    /**
     * @param EloquentCollection<int, Document> $documentsCollection
     *
     * @return void
     */
    protected function deleteDocuments(EloquentCollection $documentsCollection): void
    {
        DB::transaction(function () use ($documentsCollection) {
            $documentsCollection->each(static function ($document) {
                $this->genericRepository::delete($document->id);
            });
        });
    }
}
