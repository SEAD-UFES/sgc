<?php

namespace App\Interfaces;

use App\Models\Document;
use App\Services\Dto\StoreDocumentDto;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface EmployeeDocumentRepositoryInterface
{
    /**
     * @param string $sort
     * @param string $direction
     *
     *  @return LengthAwarePaginator
     */
    public static function all(string $sort = 'documents.id', string $direction = 'desc');

    /**
     * @param int $employeeId
     * @param int $typeId
     *
     * @return Collection<int, Document>
     */
    public static function getByEmployeeIdOfTypeId(int $employeeId, int $typeId);

    /**
     * @param int $id
     *
     * @return Document
     */
    public static function getById(int $id);

    /**
     * @param int $employeeId
     *
     * @return Collection<int, Document>
     */
    public static function getByEmployeeId(int $employeeId);

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
