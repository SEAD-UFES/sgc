<?php

namespace App\Services;

use App\Events\BondDocumentExported;
use App\Events\ModelListed;
use App\Interfaces\BondDocumentRepositoryInterface;
use App\Models\Bond;
use App\Models\BondDocument;
use App\Models\Document;
use App\Models\Employee;
use App\Services\Dto\StoreDocumentDto;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class BondDocumentService extends GenericDocumentService
{
    public function __construct(private BondDocumentRepositoryInterface $repository)
    {
        parent::__construct();
    }

    /**
     * Undocumented function
     *
     * @param string $sort
     * @param string $direction
     *
     * @return LengthAwarePaginator
     */
    public function list(string $sort = 'documents.id', string $direction = 'desc'): LengthAwarePaginator
    {
        ModelListed::dispatch(BondDocument::class);

        return $this->repository::all(sort: $sort, direction: $direction);
    }

    /**
     * Undocumented function
     *
     * @param StoreDocumentDto $storeBondDocumentDto
     *
     * @return ?Document
     */
    public function create(StoreDocumentDto $storeBondDocumentDto): ?Document
    {
        $document = null;

        DB::transaction(function () use ($storeBondDocumentDto, &$document) {
            $oldDocuments = $this->repository
                ->getByBondIdOfTypeId(
                    (int) $storeBondDocumentDto->referentId,
                    (int) $storeBondDocumentDto->documentTypeId
                );

            foreach ($oldDocuments as $oldDocument) {
                $this->repository->delete($oldDocument->id);
            }

            $document = $this->repository->create($storeBondDocumentDto);
        });

        return $document;
    }

    /**
     * Undocumented function
     *
     * @param Bond $bond
     * @param string $zipFileName
     *
     * @return string
     */
    public function zipDocuments(Bond $bond, ?string $zipFileName = null): string
    {
        /**
         * @var Collection<int, Document> $bondDocuments
         */
        $bondDocuments = $this->repository->getByBondId($bond->id);
        /**
         * @var Employee $employee
         */
        $employee = $bond->employee;
        $zipFileName = $zipFileName ?? date('Y-m-d') . '_' . $employee->name . '_' . $bond->id . '.zip';

        BondDocumentExported::dispatch($bond);

        return parent::zipGenericDocuments($bondDocuments, $zipFileName);
    }
}
