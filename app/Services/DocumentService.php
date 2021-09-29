<?php

namespace App\Services;

use Exception;
use App\Models\Bond;
use App\Models\Employee;
use App\Models\BondDocument;
use App\Models\DocumentType;
use App\CustomClasses\SgcLogger;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DocumentService
{
    public function list()
    {
        $documentClassName = class_basename($this->documentModel::class);
        SgcLogger::writeLog(target: $documentClassName, action: 'index');

        $query = $this->documentModel;
        $query = $query->AcceptRequest($this->documentModel::$accepted_filters)->filter();
        $query = $query->sortable(['updated_at' => 'desc']);
        $documents = $query->paginate(10);
        $documents->withQueryString();

        return $documents;
    }

    public function listRights()
    {
        SgcLogger::writeLog(target: 'BondsRightsIndex', action: 'index');

        $documentType = DocumentType::where('name', 'Ficha de Inscrição - Termos e Licença')->first();
        $documentsQuery = BondDocument::with('bond')
            ->where('bond_documents.document_type_id', $documentType->id)
            ->whereHas('bond', function ($bondQuery) {
                $bondQuery->whereNotNull('uaba_checked_at')->where('impediment', false);
            });

        $documentsQuery = $documentsQuery->AcceptRequest(BondDocument::$accepted_filters)->filter();
        $documentsQuery = $documentsQuery->sortable(['updated_at' => 'desc']);
        $documents = $documentsQuery->paginate(10);
        $documents->withQueryString();

        return $documents;
    }

    public function createEmployeeDocument(array $attributes)
    {
        //save the model
        $document = $this->documentModel;
        $document->employee_id = $attributes['employee_id']; // <= Particular line
        $document->document_type_id = $attributes['document_type_id'];
        $document->original_name = isset($attributes['file']) ? $attributes['file']->getClientOriginalName() : null;
        $document->file_data = isset($attributes['file']) ? $this->getFileData($attributes['file']) : null;
        $document->save();

        SgcLogger::writeLog(target: $document, action: 'store');
    }

    public function createBondDocument(array $attributes)
    {
        //save the model
        $document = $this->documentModel;
        $document->bond_id = $attributes['bonds']; // <= Particular line
        $document->document_type_id = $attributes['documentTypes'];
        $document->original_name = isset($attributes['file']) ? $attributes['file']->getClientOriginalName() : null;
        $document->file_data = isset($attributes['file']) ? $this->getFileData($attributes['file']) : null;
        $document->save();

        SgcLogger::writeLog(target: $document, action: 'store');
    }

    public static function getFileData($file)
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

    public function createManyEmployeeDocumentsStep1(array $attributes)
    {
        if (isset($attributes['files'])) {
            $files = $attributes['files'];
            $employeeDocuments = collect();
            foreach ($files as $file) {
                $tmpFileName = time() . '.' . $file->getClientOriginalName();
                $tmpFilePath = $file->storeAs('temp', $tmpFileName, 'local');

                $document = $this->documentModel;
                $document->employee_id = $attributes['employees']; // <= Particular line
                $document->original_name = $file->getClientOriginalName();
                $document->filePath = $tmpFilePath;

                $employeeDocuments->push($document);
            }
        }

        SgcLogger::writeLog(target: 'employeeDocument', action: 'store');

        return $employeeDocuments;
    }

    public function createManyBondDocumentsStep1(array $attributes)
    {
        if (isset($attributes['files'])) {
            $files = $attributes['files'];
            $bondDocuments = collect();
            foreach ($files as $file) {
                $tmpFileName = time() . '.' . $file->getClientOriginalName();
                $tmpFilePath = $file->storeAs('temp', $tmpFileName, 'local');

                $document = $this->documentModel;
                $document->bond_id = $attributes['bond_id']; // <= Particular line
                $document->original_name = $file->getClientOriginalName();
                $document->tmp_file_path = $tmpFilePath;

                $bondDocuments->push($document);
            }
        }

        SgcLogger::writeLog(target: 'bondDocument', action: 'store');

        return $bondDocuments;
    }

    public function createManyEmployeeDocumentsStep2(array $attributes)
    {
        $documentsCount = $attributes['fileSetCount'];

        DB::transaction(function () use ($attributes, $documentsCount) {

            for ($i = 0; $i < $documentsCount; $i++) {
                $filePath = $attributes['filePath_' . $i];

                $document = $this->documentModel;
                $document->employee_id = $attributes['employeeId']; // <= Particular line
                $document->document_type_id = $attributes['documentTypes_' . $i];
                $document->original_name = $attributes['fileName_' . $i];
                $document->file_data = $this->getFileDataFromPath($filePath);

                $oldDocuments = $this->documentModel;
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

        SgcLogger::writeLog(target: 'Create many EmployeeDocuments', action: 'create');
    }

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
                $document = $this->documentModel;
                $document->bond_id = $attributes['bond_id']; // <= Particular line
                $document->document_type_id = $attributes['documentTypes_' . $i];
                $document->original_name = $attributes['fileName_' . $i];
                $document->file_data = $this->getFileDataFromPath($filePath);

                //delete old same type document
                $oldDocuments = $this->documentModel;
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

        //log
        SgcLogger::writeLog(target: 'Create many BondDocuments', action: 'create');
    }

    public static function getFileDataFromPath($filePath)
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

    public function getDocument($id)
    {
        $documentClassName = class_basename($this->documentModel::class);
        SgcLogger::writeLog(target: $documentClassName, action: 'show');

        $document = $this->documentModel::find($id);

        $documentName = $document->original_name;
        $fileData = base64_decode($document->file_data);

        $FileInfoResource = finfo_open();
        $mimeType = finfo_buffer($FileInfoResource, $fileData, FILEINFO_MIME_TYPE);

        $file = collect();
        $file->name = $documentName;
        $file->mime = $mimeType;
        $file->data = $fileData;

        return $file;
    }

    public function getAllDocumentsOfEmployee(Employee $employee)
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

    public function getAllDocumentsOfBond(Bond $bond)
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
