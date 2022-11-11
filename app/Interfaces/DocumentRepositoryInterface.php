<?php

namespace App\Interfaces;

use App\Models\Document;
use App\Services\Dto\DocumentDto;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface DocumentRepositoryInterface
{
    /**
     * @param string $sort
     * @param string $direction
     *
     *  @return LengthAwarePaginator
     */
    public static function all(string $sort = 'documents.id', string $direction = 'desc');

    /**
     * @param int $bondId
     * @param int $typeId
     *
     * @return Collection<int, Document>
     */
    public static function getByBondIdOfTypeId(int $bondId, int $typeId);

    /**
     * @param int $documentId
     *
     * @return Document
     */
    public static function getById(int $documentId);

    /**
     * @param int $bondId
     *
     * @return Collection<int, Document>
     */
    public static function getByBondId(int $bondId);

    /**
     * @param DocumentDto $documentDto
     *
     * @return Document
     */
    public static function createBondDocument(DocumentDto $documentDto);

    /**
     * @param int $documentId
     *
     * @return bool
     */
    public static function delete(int $documentId);
}
