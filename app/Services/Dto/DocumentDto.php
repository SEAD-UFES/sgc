<?php

namespace App\Services\Dto;

final class DocumentDto
{
    public function __construct(
        public readonly string $fileName,
        public readonly string $fileData,
        public readonly int $documentTypeId,
        public readonly int $relatedId,
    ) {
    }
}
