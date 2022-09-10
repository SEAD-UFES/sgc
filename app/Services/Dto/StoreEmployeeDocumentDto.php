<?php

namespace App\Services\Dto;

use Illuminate\Http\UploadedFile;
use Spatie\DataTransferObject\DataTransferObject;

class StoreEmployeeDocumentDto extends DataTransferObject
{
    public string $document_type_id;

    public string $employee_id;

    public UploadedFile $file;
}
