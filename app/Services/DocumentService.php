<?php

namespace App\Services;

use Exception;
use App\Models\Bond;
use App\Models\Document;
use App\Models\Employee;
use App\Models\BondDocument;
use App\Models\DocumentType;
use App\CustomClasses\SgcLogger;
use App\Models\EmployeeDocument;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class DocumentService
{
    /**
     * Undocumented function
     *
     * @return LengthAwarePaginator
     */
    public function list(): LengthAwarePaginator
    {
        (new Document)->logListed();

        $query = new Document();
        $query = $query->where('documentable_type', $this->documentClass)->with('documentable');
        $query = $query->AcceptRequest($this->documentClass::$accepted_filters)->filter();
        $query = $query->sortable(['updated_at' => 'desc']);
        $documents = $query->paginate(10);
        $documents->withQueryString();

        return $documents;
    }

    /**
     * Undocumented function
     *
     * @return LengthAwarePaginator
     */
    public function listRights(): LengthAwarePaginator
    {
        (new Document)->logListed();

        $documentsQuery = Document::rightsWithBond();

        $documentsQuery = $documentsQuery->AcceptRequest(BondDocument::$accepted_filters)->filter();
        $documentsQuery = $documentsQuery->sortable(['updated_at' => 'desc']);
        $documents = $documentsQuery->paginate(10);
        $documents->withQueryString();

        return $documents;
    }

    /**
     * Undocumented function
     *
     * @param array $attributes
     * @return void
     */
    public function create(array $attributes)
    {
        $attributes['original_name'] = isset($attributes['file']) ? $attributes['file']->getClientOriginalName() : null;
        //$attributes['document_type_id'];
        $attributes['file_data'] = isset($attributes['file']) ? $this->getFileData($attributes['file']) : null;
        
        $attributes['documentable_type'] = $this->documentClass;
        
        DB::transaction(function () use ($attributes) {
            
            $attributes['documentable_id'] = $this->documentClass::create([
                $this->documentClass::REFERENT_ID => $attributes[$this->documentClass::REFERENT_ID]
            ])->id;
            
            $document = Document::create($attributes);
        });
    }

    /**
     * Undocumented function
     * 
     * @param Document $document
     * @return Document
     */
    public function read(Document $document): Document
    {
        $document->logViewed($document);

        return $document;
    }

    /**
     * Undocumented function
     *
     * @param UploadedFile $file
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
     * @return Collection
     */
    public function createManyEmployeeDocumentsStep1(array $attributes): Collection
    {
        if (isset($attributes['files'])) {
            $files = $attributes['files'];
            $employeeDocuments = collect();

            foreach ($files as $file) {
                $tmpFileName = time() . '.' . $file->getClientOriginalName();
                $tmpFilePath = $file->storeAs('temp', $tmpFileName, 'local');

                $document = new EmployeeDocument();
                $document->employee_id = $attributes['employee_id']; // <= Particular line
                $document->original_name = $file->getClientOriginalName();
                $document->filePath = $tmpFilePath;

                $employeeDocuments->push($document);
            }

            return $employeeDocuments;
        }
        throw new Exception('$attributes[files] not set.', 1);
    }

    /**
     * Undocumented function
     *
     * @param array $attributes
     * @return Collection
     */
    public function createManyBondDocumentsStep1(array $attributes): Collection
    {
        if (isset($attributes['files'])) {
            $files = $attributes['files'];
            $bondDocuments = collect();

            foreach ($files as $file) {
                $tmpFileName = time() . '.' . $file->getClientOriginalName();
                $tmpFilePath = $file->storeAs('temp', $tmpFileName, 'local');

                $document = new BondDocument();
                $document->bond_id = $attributes['bond_id']; // <= Particular line
                $document->original_name = $file->getClientOriginalName();
                $document->filePath = $tmpFilePath;

                $bondDocuments->push($document);
            }

            return $bondDocuments;
        }
        throw new Exception('$attributes[files] not set.', 1);
    }

    /**
     * Undocumented function
     *
     * @param array $attributes
     * @return void
     */
    public function createManyEmployeeDocumentsStep2(array $attributes)
    {
        $documentsCount = $attributes['fileSetCount'];

        DB::transaction(function () use ($attributes, $documentsCount) {

            for ($i = 0; $i < $documentsCount; $i++) {
                $filePath = $attributes['filePath_' . $i];

                $document = new EmployeeDocument();
                $document->employee_id = $attributes['employeeId']; // <= Particular line
                $document->document_type_id = $attributes['document_type_id_' . $i];
                $document->original_name = $attributes['fileName_' . $i];
                $document->file_data = $this->getFileDataFromPath($filePath);

                $oldDocuments = new EmployeeDocument();
                $oldDocuments = $oldDocuments
                    ->where('employee_id', $document->employee_id) // <= Particular line
                    ->where('document_type_id', $document->document_type_id)
                    ->get();
                foreach ($oldDocuments as $old) $old->delete();

                $document->save();
            }
        });

        //delete tmp_files
        for ($i = 0; $i < $documentsCount; $i++) {
            $filePath = $attributes['filePath_' . $i];
            Storage::delete($filePath);
        }
    }

    /**
     * Undocumented function
     *
     * @param array $attributes
     * @return void
     */
    public function createManyBondDocumentsStep2(array $attributes)
    {
        //number of files
        $documentsCount = $attributes['bondDocumentsCount'];

        //save all documents
        DB::transaction(function () use ($attributes, $documentsCount) {
            //save model for each file
            for ($i = 0; $i < $documentsCount; $i++) {
                //get file_path
                $filePath = $attributes['filePath_' . $i];

                //set the model
                $document = new BondDocument();
                $document->bond_id = $attributes['bond_id']; // <= Particular line
                $document->document_type_id = $attributes['document_type_id_' . $i];
                $document->original_name = $attributes['fileName_' . $i];
                $document->file_data = $this->getFileDataFromPath($filePath);

                //delete old same type document
                $oldDocuments = new BondDocument();
                $oldDocuments = $oldDocuments
                    ->where('bond_id', $document->bond_id) // <= Particular line
                    ->where('document_type_id', $document->document_type_id)
                    ->get();
                foreach ($oldDocuments as $old) $old->delete();

                //save new BondDocument
                $document->save();
            }
        });

        //delete tmp_files
        for ($i = 0; $i < $documentsCount; $i++) {
            $filePath = $attributes['filePath_' . $i];
            Storage::delete($filePath);
        }
    }

    /**
     * Undocumented function
     *
     * @param string $filePath
     * @return string
     */
    public function getFileDataFromPath(string $filePath): string
    {
        //if filePath
        if ($filePath) {
            $fileContent = file_get_contents(base_path('storage/app/' . $filePath), true);
            $fileContentBase64 = base64_encode($fileContent);
            return $fileContentBase64;
        }

        //if no file
        return null;
    }

    /**
     * Undocumented function
     *
     * @param integer $id
     * @return Collection
     */
    public function getDocument(int $id): Collection
    {
        $document = Document::find($id);

        $document->logViewed($document);

        $documentName = $document->original_name;
        $fileData = base64_decode($document->file_data);

        $FileInfoResource = finfo_open();
        $mimeType = finfo_buffer($FileInfoResource, $fileData, FILEINFO_MIME_TYPE);

        $file = collect();
        $file->name = $documentName;
        $file->mime = $mimeType;
        $file->data = $fileData;
        $file->class = $document->documentable_type;

        return $file;
    }

    /**
     * Undocumented function
     *
     * @param Employee $employee
     * @return string
     */
    public function getAllDocumentsOfEmployee(Employee $employee): string
    {
        SgcLogger::writeLog(target: $employee, action: 'getAllDocumentsOfEmployee');

        $documents = $employee->employeeDocuments; // <= Particular line

        $zipFileName = date('Y-m-d') . '_' . $employee->name . '.zip'; // <= Particular line
        $zip = new \ZipArchive();

        if ($zip->open($zipFileName, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === TRUE) {

            foreach ($documents as $document) $zip->addFromString($document->original_name, base64_decode($document->file_data));

            $zip->close();

            return $zipFileName;
        }

        throw new Exception('failed: $zip->open()', 1);
    }

    /**
     * Undocumented function
     *
     * @param Bond $bond
     * @return string
     */
    public function getAllDocumentsOfBond(Bond $bond): string
    {
        SgcLogger::writeLog(target: $bond, action: 'getAllDocumentsOfBond');

        $documents = $bond->bondDocuments; // <= Particular line

        $zipFileName = date('Y-m-d') . '_' . $bond->employee->name . '_' . $bond->id . '.zip'; // <= Particular line
        $zip = new \ZipArchive();

        if ($zip->open($zipFileName, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === TRUE) {

            foreach ($documents as $document) $zip->addFromString($document->original_name, base64_decode($document->file_data));

            $zip->close();

            return $zipFileName;
        }

        throw new Exception('failed: $zip->open()', 1);
    }
}
