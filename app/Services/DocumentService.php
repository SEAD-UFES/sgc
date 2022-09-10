<?php

namespace App\Services;

use App\Events\ModelListed;
use App\Events\ModelRead;
use App\Models\BondDocument;
use App\Models\Document;
use App\Models\EmployeeDocument;
use Exception;
use finfo;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DocumentService implements DocumentServiceInterface
{
    protected ?string $referentId;

    protected ?string $documentClass;

    protected ?string $referentClass;

    public function __construct(?string $documentClass, ?string $referentClass)
    {
        $this->documentClass = $documentClass;
        $this->referentClass = $referentClass;
        $this->referentId = is_null($this->documentClass) ? null : $this->documentClass::referentId();
    }

    /**
     * Undocumented function
     *
     * @param string|null $sort
     * @param string|null $direction
     *
     * @return LengthAwarePaginator
     */
    public function list(?string $sort = 'documents.id', ?string $direction = 'desc'): LengthAwarePaginator
    {
        $sort = $sort ?? 'documents.id';
        $direction = $direction ?? 'desc';

        /**
         * @var array<int, string> $sortable
         */
        $sortable = $this->documentClass::$sortable;

        /**
         * @var array<int, string> $directions
         */
        $directions = ['asc', 'desc'];

        if (! in_array($sort, $sortable) || ! in_array($direction, $directions)) {
            $sort = 'documents.id';
            $direction = 'desc';
        }

        ModelListed::dispatch($this->documentClass);

        /**
         * @var BondDocument|EmployeeDocument $documentInstance
         */
        $documentInstance = new $this->documentClass();

        $query = $documentInstance->queryDocuments();
        $query = $query->AcceptRequest($this->documentClass::$accepted_filters)->filter();
        $query = $query->orderBy($sort, $direction);

        $documents = $query->paginate(10);
        $documents->withQueryString();

        return $documents;
    }

    /**
     * Undocumented function
     *
     * @param array<string, string|UploadedFile> $attributes
     *
     * @return void
     */
    public function create(array $attributes): void
    {
        /**
         * @var UploadedFile $uploadedFile
         */
        $uploadedFile = $attributes['file'];

        $attributes['original_name'] = $uploadedFile->getClientOriginalName();

        $attributes['file_data'] = $this->getFileData($uploadedFile);

        $attributes['documentable_type'] = $this->documentClass;

        DB::transaction(function () use ($attributes) {
            // Get old versions of this document type
            $oldDocuments = $this->getOldDocuments($attributes);

            // Delete old versions of this document type
            $this->deleteOldDocuments($oldDocuments);

            // 'employee_id' or 'bond_id'
            $referentId = $this->documentClass::referentId();

            /** @var EmployeeDocument|BondDocument $documentable */
            $documentable = $this->documentClass::create([
                $referentId => $attributes[$referentId],
            ]);

            /** @var Document $document */
            $document = new Document($attributes);
            $documentable->document()->save($document);

            return $document;
        });
    }

    /**
     * Undocumented function
     *
     * @param Document $document
     *
     * @return Document
     */
    public function read(Document $document): Document
    {
        ModelRead::dispatch($document);

        return $document;
    }

    /**
     * Undocumented function
     *
     * @param UploadedFile $file
     *
     * @return string
     */
    public function getFileData(UploadedFile $file): string
    {
        $fileName = time() . '.' . $file->getClientOriginalName();
        /**
         * @var string $filePath
         */
        $filePath = $file->storeAs('temp', $fileName, 'local');

        $fileContentBase64 = $this->getFileDataFromPath($filePath);

        Storage::delete($filePath);

        return $fileContentBase64;
    }

    /**
     * Undocumented function
     *
     * @param array<string, string|array<int, UploadedFile>> $attributes
     *
     * @return SupportCollection<int, array<string, string>>
     */
    public function createManyDocumentsStep1(array $attributes): SupportCollection
    {
        /**
         * @var array<int, UploadedFile> $uploadedFiles
         */
        $uploadedFiles = $attributes['files'];

        /**
         * @var array<string, string> $document
         */
        $document = [];

        /**
         * @var SupportCollection<int, array<string, string>> $documents
         */
        $documents = new SupportCollection();

        foreach ($uploadedFiles as $file) {
            $tmpFileName = time() . '.' . $file->getClientOriginalName();
            /**
             * @var string $tmpFilePath
             */
            $tmpFilePath = $file->storeAs('temp', $tmpFileName, 'local');

            /**
             * @var string $referentIdColumnName
             */
            $referentIdColumnName = $this->documentClass::referentId();

            /**
             * @var array<string, string> $document
             */
            $document[$referentIdColumnName] = $attributes[$referentIdColumnName];
            $document['original_name'] = $file->getClientOriginalName();
            $document['filePath'] = $tmpFilePath;

            $documents->push($document);
        }

        return $documents;
    }

    /**
     * Undocumented function
     *
     * @param array<string, string> $attributes
     *
     * @return void
     */
    public function createManyDocumentsStep2(array $attributes): void
    {
        $documentsCount = $attributes['fileSetCount'];

        /**
         * @var string $referentIdColumnName
         */
        $referentIdColumnName = $this->documentClass::referentId();

        DB::transaction(function () use ($attributes, $documentsCount, $referentIdColumnName) {
            $document = [];
            $document[$referentIdColumnName] = $attributes[$referentIdColumnName];
            $document['documentable_type'] = $this->documentClass;

            for ($i = 0; $i < $documentsCount; $i++) {
                $document['original_name'] = $attributes['fileName_' . $i];
                $document['document_type_id'] = $attributes['document_type_id_' . $i];

                $filePath = $attributes['filePath_' . $i];
                $document['file_data'] = $this->getFileDataFromPath($filePath);

                // Get old versions of this document type
                $oldDocuments = $this->getOldDocuments($document);

                // Delete old versions of this document type
                $this->deleteOldDocuments($oldDocuments);

                $document['documentable_id'] = $this->documentClass::create([
                    $referentIdColumnName => $attributes[$referentIdColumnName],
                ])->id;

                Document::create($document);

                //delete tmp_files
                Storage::delete($filePath);
            }
        });
    }

    /**
     * Undocumented function
     *
     * @param string $filePath
     *
     * @throws Exception
     *
     * @return string
     */
    public function getFileDataFromPath(string $filePath): string
    {
        //if filePath
        if ($filePath !== '' && $filePath !== '0') {
            /**
             * @var string $fileContent
             */
            $fileContent = file_get_contents(base_path('storage/app/' . $filePath), true);
            if ($fileContent !== false) {
                /**
                 * @var string $fileContentBase64
                 */
                $fileContentBase64 = base64_encode($fileContent);

                return $fileContentBase64;
            }
        }

        //if no file
        throw new Exception('No file to get data from');
    }

    /**
     * Undocumented function
     *
     * @param int $id
     *
     * @return SupportCollection<string, string>
     */
    public function getDocument(int $id): SupportCollection
    {
        /**
         * @var Document $document
         */
        $document = Document::find($id);

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
        $file->put('class', $document->documentable_type);
        $file->put('isRights', (string) $document->isRights());

        return $file;
    }

    /**
     * Get the value of documentClass
     *
     * @return string
     */
    public function getDocumentClass(): string
    {
        return $this->documentClass;
    }

    /**
     * @param EloquentCollection<int, EmployeeDocument|BondDocument> $documentables
     * @param string $zipFileName
     *
     * @return string
     *
     * @throws Exception
     */
    protected function exportGenericDocuments(EloquentCollection $documentables, string $zipFileName): string
    {
        $zip = new \ZipArchive();

        if ($zip->open($zipFileName, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === true) {
            foreach ($documentables as $documentable) {
                /**
                 * @var Document $baseDocument
                 */
                $baseDocument = $documentable->document;
                $documentName = $baseDocument->original_name;
                $documentData = base64_decode($baseDocument->file_data);
                $zip->addFromString($documentName, $documentData);
            }

            $zip->close();

            return $zipFileName;
        }

        throw new Exception('failed: $zip->open()', 1);
    }

    /**
     * Undocumented function
     *
     * @param array<string, string> $document
     *
     * @return EloquentCollection<int, Document>
     */
    private function getOldDocuments($document): EloquentCollection
    {
        $referentId = $this->documentClass::referentId();

        /* Quering for the documents with specific documentable type (EmployeeDocument or BondDocument) and document type
        where documentables referent (employee or bond) match the data from the form. */
        /**
         * @var EloquentCollection<int, Document> $oldDocuments
         */
        $oldDocuments = Document::whereHasMorph(
            'documentable',
            $this->documentClass,
            static function (Builder $query) use ($document, $referentId) {
                $query->where($referentId, $document[$referentId]);
            }
        )->where('document_type_id', $document['document_type_id'])->get();

        return $oldDocuments;
    }

    /**
     * @param EloquentCollection<int, Document> $oldDocuments
     *
     * @return void
     */
    private function deleteOldDocuments(EloquentCollection $oldDocuments): void
    {
        /* If there are old documents, get the documentables */
        $oldDocumentables = $oldDocuments->map(static function ($oldDocument) {
            return $oldDocument->documentable;
        });

        /* If there are old documentables, delete them */
        $oldDocumentables->each(static function ($oldDocumentable) {
            $oldDocumentable->delete();
        });

        /* If there are old documents, delete them */
        $oldDocuments->each(static function ($oldDocument) {
            $oldDocument->delete();
        });
    }
}
