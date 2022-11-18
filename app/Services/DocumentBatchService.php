<?php

namespace App\Services;

use App\Models\Document;
use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\Facades\Storage;

class DocumentBatchService
{
    protected ?string $referentId;

    protected ?string $documentClass;

    protected ?string $referentClass;

    public function __construct(?string $documentClass, ?string $referentClass)
    {
        $this->documentClass = $documentClass;
        $this->referentClass = $referentClass;
        $this->referentId = is_null($this->documentClass) ? null : $this->documentClass::referentIdColumnName();
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
            $referentIdColumnName = $this->documentClass::referentIdColumnName();

            $document[$referentIdColumnName] = $attributes[$referentIdColumnName];
            $document['file_name'] = $file->getClientOriginalName();
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
        $referentIdColumnName = $this->documentClass::referentIdColumnName();

        DB::transaction(function () use ($attributes, $documentsCount, $referentIdColumnName) {
            $document = [];
            $document[$referentIdColumnName] = $attributes[$referentIdColumnName];
            $document['documentable_type'] = $this->documentClass;

            for ($i = 0; $i < $documentsCount; $i++) {
                $document['file_name'] = $attributes['fileName_' . $i];
                $document['document_type_id'] = $attributes['document_type_id_' . $i];

                $filePath = $attributes['filePath_' . $i];
                $document['file_data'] = $this->getFileDataFromPath($filePath);

                // Get old versions of this document type
                //$oldDocuments = $this->getOldDocuments($document);

                // Delete old versions of this document type
                //$this->deleteDocuments($oldDocuments);

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
                return base64_encode($fileContent);
            }
        }

        //if no file
        throw new Exception('No file to get data from');
    }
}
