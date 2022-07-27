<?php

namespace App\Services;

use App\Helpers\SgcLogHelper;
use App\Models\Bond;
use App\Models\BondDocument;
use App\Models\Document;
use App\Models\Employee;
use App\Models\EmployeeDocument;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DocumentService
{
    public string $documentClass;

    /**
     * Undocumented function
     *
     * @param string|null $sort
     * @param string|null $direction
     *
     * @return LengthAwarePaginator
     */
    public function list(?string $sort = null, ?string $direction = null): LengthAwarePaginator
    {
        (new Document())->logListed();

        $query = (new $this->documentClass())->queryDocuments();
        $query = $query->AcceptRequest($this->documentClass::$accepted_filters)->filter();

        if (in_array($sort, $this->documentClass::$sortable) && in_array($direction, ['asc', 'desc'])) {
            $query = $query->orderBy($sort, $direction);
        } else {
            $query = $query->orderBy('documents.updated_at', 'desc');
        }

        $documents = $query->paginate(10);
        $documents->withQueryString();

        return $documents;
    }

    /**
     * Undocumented function
     *
     * @param string|null $sort
     * @param string|null $direction
     *
     * @return LengthAwarePaginator
     */
    public function listRights(?string $sort = null, ?string $direction = null): LengthAwarePaginator
    {
        (new Document())->logListed();

        $query = (new $this->documentClass())->queryRights();
        $query = $query->AcceptRequest($this->documentClass::$accepted_filters)->filter();
        if (in_array($sort, $this->documentClass::$sortable) && in_array($direction, ['asc', 'desc'])) {
            $query = $query->orderBy($sort, $direction);
        } else {
            $query = $query->orderBy('documents.updated_at', 'desc');
        }

        $documents = $query->paginate(10);
        $documents->withQueryString();

        return $documents;
    }

    /**
     * Undocumented function
     *
     * @param array $attributes
     *
     * @return void
     */
    public function create(array $attributes)
    {
        $attributes['original_name'] = isset($attributes['file']) ? $attributes['file']->getClientOriginalName() : null;

        $attributes['file_data'] = isset($attributes['file']) ? $this->getFileData($attributes['file']) : null;

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
        $document->logFetched($document);

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
        //if file
        if (isset($file)) {
            $fileName = time() . '.' . $file->getClientOriginalName();
            $filePath = $file->storeAs('temp', $fileName, 'local');
            $fileContent = file_get_contents(base_path('storage/app/' . $filePath), true);
            $fileContentBase64 = base64_encode($fileContent);
            Storage::delete($filePath);

            return $fileContentBase64;
        }

        //if no file
        return null;
    }

    /**
     * Undocumented function
     *
     * @param array $attributes
     *
     * @return Collection
     */
    public function createManyDocumentsStep1(array $attributes): Collection
    {
        if (isset($attributes['files'])) {
            $files = $attributes['files'];

            $document = [];
            $documents = collect();

            foreach ($files as $file) {
                $tmpFileName = time() . '.' . $file->getClientOriginalName();
                $tmpFilePath = $file->storeAs('temp', $tmpFileName, 'local');

                $document[$this->documentClass::REFERENT_ID] = $attributes[$this->documentClass::REFERENT_ID];
                $document['original_name'] = $file->getClientOriginalName();
                $document['filePath'] = $tmpFilePath;

                $documents->push($document);
            }

            return $documents;
        }
        throw new Exception('$attributes[files] not set.', 1);
    }

    /**
     * Undocumented function
     *
     * @param array $attributes
     *
     * @return void
     */
    public function createManyDocumentsStep2(array $attributes)
    {
        $documentsCount = $attributes['fileSetCount'];

        DB::transaction(function () use ($attributes, $documentsCount) {
            $document = [];
            $document[$this->documentClass::REFERENT_ID] = $attributes[$this->documentClass::REFERENT_ID];
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
                    $this->documentClass::REFERENT_ID => $attributes[$this->documentClass::REFERENT_ID],
                ])->id;
                /*
                $document['documentable_id'] = $this->documentClass::create(['employee_id' => $attributes['employee_id']])->id;
                $document['documentable_id'] = EmployeeDocument::create(['employee_id' => $attributes['employee_id']])->id;
                */

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
     * @return string
     */
    public function getFileDataFromPath(string $filePath): string
    {
        //if filePath
        if ($filePath) {
            $fileContent = file_get_contents(base_path('storage/app/' . $filePath), true);
            return base64_encode($fileContent);
        }

        //if no file
        return null;
    }

    /**
     * Undocumented function
     *
     * @param int $id
     *
     * @return Collection
     */
    public function getDocument(int $id): Collection
    {
        $document = Document::find($id);

        $document->logFetched($document);

        $documentName = $document->original_name;
        $fileData = base64_decode($document->file_data);

        $FileInfoResource = finfo_open();
        $mimeType = finfo_buffer($FileInfoResource, $fileData, FILEINFO_MIME_TYPE);

        $file = collect();
        $file->name = $documentName;
        $file->mime = $mimeType;
        $file->data = $fileData;
        $file->class = $document->documentable_type;
        $file->isRights = $document->isRights();

        return $file;
    }

    /**
     * Undocumented function
     *
     * @param Employee $employee
     *
     * @return string
     */
    public function exportEmployeeDocuments(Employee $employee): string
    {
        SgcLogHelper::writeLog(target: $employee, action: 'exportEmployeeDocuments');

        $documentables = $employee->employeeDocuments; // <= Particular line
        $zipFileName = date('Y-m-d') . '_' . $employee->name . '.zip'; // <= Particular line

        return $this->exportDocuments($documentables, $zipFileName);
    }

    /**
     * Undocumented function
     *
     * @param Bond $bond
     *
     * @return string
     */
    public function exportBondDocuments(Bond $bond): string
    {
        SgcLogHelper::writeLog(target: $bond, action: 'exportBondDocuments');

        $documentables = $bond->bondDocuments; // <= Particular line
        $zipFileName = date('Y-m-d') . '_' . $bond->employee->name . '_' . $bond->id . '.zip'; // <= Particular line

        return $this->exportDocuments($documentables, $zipFileName);
    }

    /**
     * @param Collection $documentables
     * @param string $zipFileName
     *
     * @return string
     *
     * @throws Exception
     */
    public function exportDocuments(Collection $documentables, string $zipFileName): string
    {
        $zip = new \ZipArchive();

        if ($zip->open($zipFileName, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === true) {
            foreach ($documentables as $documentable) {
                $documentName = $documentable->document->original_name;
                $documentData = base64_decode($documentable->document->file_data);
                $zip->addFromString($documentName, $documentData);
            }

            $zip->close();

            return $zipFileName;
        }

        throw new Exception('failed: $zip->open()', 1);
    }

    private function getOldDocuments($document): Collection
    {
        $referentId = $this->documentClass::referentId();

        /* Quering for the documents with specific documentable type (EmployeeDocument or BondDocument) and document type
        where documentables referent (employee or bond) match the data from the form. */
        return Document::whereHasMorph(
            'documentable',
            $this->documentClass,
            static function (Builder $query) use ($document, $referentId) {
                $query->where($referentId, $document[$referentId]);
            }
        )->where('document_type_id', $document['document_type_id'])->get();
    }

    private function deleteOldDocuments(Collection $oldDocuments): void
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
