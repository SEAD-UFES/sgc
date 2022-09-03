<?php

namespace App\Services;

use App\Events\BondDocumentExported;
use App\Events\ModelListed;
use App\Models\Bond;
use App\Models\BondDocument;
use App\Models\Document;
use App\Models\Employee;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class BondDocumentService extends DocumentService
{
    public function __construct()
    {
        parent::__construct(BondDocument::class, Bond::class);
    }

    /**
     * Undocumented function
     *
     * @param ?string $sort
     * @param ?string $direction
     *
     * @return LengthAwarePaginator
     */
    public function listRights(?string $sort = 'documents.id', ?string $direction = 'desc'): LengthAwarePaginator
    {
        $sort = $sort ?? 'documents.id';
        $direction = $direction ?? 'desc';

        /**
         * @var array<int, string> $sortable
         */
        $sortable = BondDocument::$sortable;

        /**
         * @var array<int, string> $directions
         */
        $directions = ['asc', 'desc'];

        if (! in_array($sort, $sortable) || ! in_array($direction, $directions)) {
            $sort = 'documents.id';
            $direction = 'desc';
        }

        ModelListed::dispatch(BondDocument::class);

        /**
         * @var BondDocument $documentInstance
         */
        $documentInstance = new BondDocument();

        /**
         * @var Builder<Document> $query
         */
        $query = $documentInstance->queryRights();
        $query = $query->AcceptRequest(BondDocument::$accepted_filters)->filter();
        $query = $query->orderBy($sort, $direction);

        $documents = $query->paginate(10);
        $documents->withQueryString();

        return $documents;
    }

    /**
     * Undocumented function
     *
     * @param Bond $bond
     * @param string $zipFileName
     *
     * @return string
     */
    public function exportDocuments(Bond $bond, ?string $zipFileName = null): string
    {
        /**
         * @var Collection<int, BondDocument> $documentables
         */
        $documentables = $bond->bondDocuments()->get(); // <= Particular line
        /**
         * @var Employee $employee
         */
        $employee = $bond->employee;
        $zipFileName = $zipFileName ?? date('Y-m-d') . '_' . $employee->name . '_' . $bond->id . '.zip'; // <= Particular line

        BondDocumentExported::dispatch($bond);

        return parent::exportGenericDocuments($documentables, $zipFileName);
    }
}
