<?php

namespace App\Services\Dto;

use Illuminate\Http\UploadedFile;
use Spatie\DataTransferObject\DataTransferObject;

class StoreBondDocumentDto extends DataTransferObject
{
    public string $document_type_id;

    public string $bond_id;

    public UploadedFile $file;
}
