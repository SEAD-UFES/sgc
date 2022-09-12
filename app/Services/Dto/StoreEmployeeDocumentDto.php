<?php

namespace App\Services\Dto;

use Illuminate\Http\UploadedFile;
use Spatie\DataTransferObject\DataTransferObject;

class StoreEmployeeDocumentDto extends DataTransferObject
{
    public string $documentTypeId;

    public string $employeeId;

    public UploadedFile $file;
}
