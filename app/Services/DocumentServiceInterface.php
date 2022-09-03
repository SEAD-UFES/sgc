<?php

namespace App\Services;

use App\Models\Document;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection as SupportCollection;

interface DocumentServiceInterface
{
    /**
     * Undocumented function
     *
     * @param string|null $sort
     * @param string|null $direction
     *
     * @return LengthAwarePaginator
     */
    public function list(?string $sort = 'id', ?string $direction = 'desc'): LengthAwarePaginator;

    /**
     * Undocumented function
     *
     * @param array<string, string|UploadedFile> $attributes
     *
     * @return void
     */
    public function create(array $attributes): void;

    /**
     * Undocumented function
     *
     * @param Document $document
     *
     * @return Document
     */
    public function read(Document $document): Document;

    /**
     * Undocumented function
     *
     * @param UploadedFile $file
     *
     * @return string
     */
    public function getFileData(UploadedFile $file): string;

    /**
     * Undocumented function
     *
     * @param array<string, string|array<int, UploadedFile>> $attributes
     *
     * @return SupportCollection<int, array<string, string>>
     */
    public function createManyDocumentsStep1(array $attributes): SupportCollection;

    /**
     * Undocumented function
     *
     * @param array<string, string> $attributes
     *
     * @return void
     */
    public function createManyDocumentsStep2(array $attributes): void;

    /**
     * Undocumented function
     *
     * @param string $filePath
     *
     * @throws Exception
     *
     * @return string
     */
    public function getFileDataFromPath(string $filePath): string;

    /**
     * Undocumented function
     *
     * @param int $id
     *
     * @return SupportCollection<string, string>
     */
    public function getDocument(int $id): SupportCollection;

    /**
     * Get the value of documentClass
     *
     * @return string
     */
    public function getDocumentClass(): string;
}
