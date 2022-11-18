<?php

namespace App\Services;

use App\Events\ModelListed;
use App\Events\ModelRead;
use App\Models\Document;
use App\Repositories\RightsDocumentRepository;
use finfo;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class RightsDocumentService
{
    public function __construct(private RightsDocumentRepository $repository)
    {
    }

    /**
     * Undocumented function
     *
     * @param ?string $direction
     * @param ?string $sort
     *
     * @return LengthAwarePaginator<Document>
     */
    public function list(?string $direction, ?string $sort): LengthAwarePaginator
    {
        ModelListed::dispatch(Document::class);

        return $this->repository::all(direction: $direction, sort: $sort);
    }

    /**
     * Undocumented function
     *
     * @param int $id
     *
     * @return Collection<string, string>
     */
    public function assembleDocument(int $id): Collection
    {
        /**
         * @var Document $document
         */
        $document = $this->repository::getById($id);

        ModelRead::dispatch($document);

        /**
         * @var string $documentName
         */
        $documentName = $document->file_name;

        /**
         * @var string $fileData
         */
        $fileData = base64_decode($document->file_data);

        /**
         * @var finfo $fileInfoResource
         */
        $fileInfoResource = finfo_open();

        /**
         * @var string $mimeType
         */
        $mimeType = finfo_buffer($fileInfoResource, $fileData, FILEINFO_MIME_TYPE);

        /**
         * @var Collection<string, string> $file
         */
        $file = new Collection();
        $file->put('name', $documentName);
        $file->put('mime', $mimeType);
        $file->put('data', $fileData);

        return $file;
    }
}
