<?php

namespace App\Services;

use App\Events\ModelListed;
use App\Models\BondDocument;
use App\Repositories\RightsDocumentRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class RightsDocumentService extends GenericDocumentService
{
    public function __construct(private RightsDocumentRepository $repository)
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

        return $this->repository::getAllDocuments(sort: $sort, direction: $direction);
    }
}
