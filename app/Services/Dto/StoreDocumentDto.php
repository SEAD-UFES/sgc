<?php

namespace App\Services\Dto;

use Spatie\DataTransferObject\DataTransferObject;

class StoreDocumentDto extends DataTransferObject
{
    public string $fileName;

    public string $fileData;

    public string $documentTypeId;

    public string $referentId;
}
