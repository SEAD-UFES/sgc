<?php

namespace App\Services;

use App\Events\ModelListed;
use App\Models\Document;
use App\Repositories\RightsDocumentRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class RightsDocumentService
{
    public function __construct(private RightsDocumentRepository $repository)
    {
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
        ModelListed::dispatch(Document::class);

        return $this->repository::getAllDocuments(sort: $sort, direction: $direction);
    }
}
