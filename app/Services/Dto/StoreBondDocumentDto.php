<?php

namespace App\Services\Dto;

use Illuminate\Http\UploadedFile;
use Spatie\DataTransferObject\DataTransferObject;

class StoreBondDocumentDto extends DataTransferObject
{
    public string $documentTypeId;

    public string $bondId;

    public UploadedFile $file;
}
