<?php

namespace App\Interfaces;

use App\Models\Document;
use App\Services\Dto\StoreDocumentDto;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface BondDocumentRepositoryInterface
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
     * @param int $id
     *
     * @return Document
     */
    public static function getById(int $id);

    /**
     * @param int $bondId
     *
     * @return Collection<int, Document>
     */
    public static function getByBondId(int $bondId);

    /**
     * @param StoreDocumentDto $documentDto
     *
     * @return Document
     */
    public static function create(StoreDocumentDto $documentDto);

    /**
     * @param int $id
     *
     * @return bool
     */
    public static function delete(int $id);
}
