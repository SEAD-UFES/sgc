<?php

namespace App\Repositories;

use App\Helpers\DocumentRepositoryHelper;
use App\Interfaces\DocumentRepositoryInterface;
use App\Models\Bond;
use App\Models\Document;
use App\Services\Dto\DocumentDto;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class DocumentRepository implements DocumentRepositoryInterface
{
    /**
     * Undocumented function
     *
     * @param string $sort
     * @param string $direction
     *
     * @return LengthAwarePaginator
     */
    public static function all(string $sort = 'documents.id', string $direction = 'desc'): LengthAwarePaginator
    {
        $sort = DocumentRepositoryHelper::validateSort(Document::class, $sort);
        $direction = DocumentRepositoryHelper::validateDirection($direction);

        /**
         * @var Builder<Document> $query
         */
        $query = Document::select([
            'employees.name as employee_name',
            'roles.name as role_name',
            'courses.name as course_name',
            'documents.file_name',
            'document_types.name as document_type',
            'documents.id',
            'employees.id as employee_id',
            'bonds.id as bond_id',
        ])
            ->with('related')
            ->where('documents.related_type', 'App\Models\Bond')
            ->join('document_types', 'documents.document_type_id', '=', 'document_types.id')
            ->join('bonds', 'documents.related_id', '=', 'bonds.id')
            ->join('employees', 'bonds.employee_id', '=', 'employees.id')
            ->join('roles', 'bonds.role_id', '=', 'roles.id')
            ->join('bond_course', 'bonds.id', '=', 'bond_course.bond_id')
            ->join('courses', 'bond_course.course_id', '=', 'courses.id')
            ->AcceptRequest(Document::class::$accepted_filters)->filter() // AcceptRequest: mehdi-fathi/eloquent-filter
            ->orderBy($sort, $direction);

        /** @var LengthAwarePaginator<Document> $documents */
        $documents = $query->paginate(10);
        $documents->withQueryString(); // AbstractPaginator->withQueryString(): append all of the current request's query string values to the pagination links [https://laravel.com/docs/9.x/pagination]

        return $documents;
    }
    /**
     * @param int $documentId
     *
     * @return Document
     */
    public static function getById(int $documentId)
    {
        return Document::where('id', $documentId)->with('related')->firstOrFail();
    }

    /**
     * @param int $bondId
     * @param int $typeId
     *
     * @return Collection<int, Document>
     */
    public static function getByBondIdOfTypeId(int $bondId, int $typeId)
    {
        return Document::where('document_type_id', $typeId)->whereHasMorph('related', Bond::class, static function ($query) use ($bondId) {
            $query->where('related_id', $bondId);
        })->with('related')->orderBy('id', 'desc')->get();
    }

    /**
     * @param int $typeId
     * @param string $sort
     * @param string $direction
     *
     * @return LengthAwarePaginator
     */
    public static function getByTypeId(int $typeId, string $sort = 'documents.id', string $direction = 'desc'): LengthAwarePaginator
    {
        $sort = DocumentRepositoryHelper::validateSort(Document::class, $sort);
        $direction = DocumentRepositoryHelper::validateDirection($direction);

        /**
         * @var Builder<Document> $query
         */
        $query = Document::where('document_type_id', $typeId)
            ->whereHasMorph('related', Bond::class)
            ->with('related')
            ->orderBy($sort, $direction);

        /** @var LengthAwarePaginator<Document> $documents */
        $documents = $query->paginate(10);
        $documents->withQueryString(); // AbstractPaginator->withQueryString(): append all of the current request's query string values to the pagination links [https://laravel.com/docs/9.x/pagination]

        return $documents;
    }

    /**
     * @param int $bondId
     *
     * @return Collection<int, Document>
     */
    public static function getByBondId(int $bondId)
    {
        return Document::whereHasMorph('related', Bond::class, static function ($query) use ($bondId) {
            $query->where('id', $bondId);
        })->with('related')->orderBy('id', 'desc')->get();
    }

    /**
     * @param DocumentDto $documentDto
     *
     * @return Document
     */
    public static function createBondDocument(DocumentDto $documentDto)
    {
        /** @var Document $document */
        return Document::create([
            'file_name' => $documentDto->fileName,
            'document_type_id' => $documentDto->documentTypeId,
            'related_type' => Bond::class,
            'related_id' => $documentDto->relatedId,
            'file_data' => $documentDto->fileData,
        ]);
    }

    /**
     * @param int $documentId
     *
     * @return bool
     */
    public static function delete(int $documentId)
    {
        $document = self::getById($documentId);

        return $document->delete() ?? false;
    }
}
