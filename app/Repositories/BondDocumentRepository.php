<?php

namespace App\Repositories;

use App\Helpers\DocumentRepositoryHelper;
use App\Interfaces\BondDocumentRepositoryInterface;
use App\Models\BondDocument;
use App\Models\Document;
use App\Services\Dto\StoreDocumentDto;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class BondDocumentRepository extends GenericDocumentRepository implements BondDocumentRepositoryInterface
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
        $sort = DocumentRepositoryHelper::validateSort(BondDocument::class, $sort);
        $direction = DocumentRepositoryHelper::validateDirection($direction);

        /**
         * @var Builder<Document> $query
         */
        $query = Document::select([
            'employees.name as employee_name',
            'roles.name as role_name',
            'courses.name as course_name',
            'documents.original_name',
            'document_types.name as document_type',
            'documents.id',
            'employees.id as employee_id',
            'bonds.id as bond_id',
        ])
            ->whereHasMorph('documentable', BondDocument::class)
            ->with('documentable')
            ->join('document_types', 'documents.document_type_id', '=', 'document_types.id')
            ->join('bond_documents', 'documents.documentable_id', '=', 'bond_documents.id')
            ->join('bonds', 'bond_documents.bond_id', '=', 'bonds.id')
            ->join('employees', 'bonds.employee_id', '=', 'employees.id')
            ->join('roles', 'bonds.role_id', '=', 'roles.id')
            ->join('courses', 'bonds.course_id', '=', 'courses.id')
            ->AcceptRequest(BondDocument::class::$accepted_filters)->filter() // AcceptRequest: mehdi-fathi/eloquent-filter
            ->orderBy($sort, $direction);

        $documents = $query->paginate(10);
        $documents->withQueryString(); // AbstractPaginator->withQueryString(): append all of the current request's query string values to the pagination links [https://laravel.com/docs/9.x/pagination]

        return $documents;
    }

    /**
     * @param int $bondId
     * @param int $typeId
     *
     * @return Collection<int, Document>
     */
    public static function getByBondIdOfTypeId(int $bondId, int $typeId)
    {
        return Document::where('document_type_id', $typeId)->whereHasMorph('documentable', BondDocument::class, static function ($query) use ($bondId) {
            $query->where('bond_documents.bond_id', $bondId);
        })->with('documentable')->orderBy('id', 'desc')->get();
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
        $sort = DocumentRepositoryHelper::validateSort(BondDocument::class, $sort);
        $direction = DocumentRepositoryHelper::validateDirection($direction);

        /**
         * @var Builder<Document> $query
         */
        $query = Document::where('document_type_id', $typeId)
            ->whereHasMorph('documentable', BondDocument::class)
            ->with('documentable')
            ->orderBy($sort, $direction);

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
        return Document::whereHasMorph('documentable', BondDocument::class, static function ($query) use ($bondId) {
            $query->where('bond_documents.bond_id', $bondId);
        })->with('documentable')->orderBy('id', 'desc')->get();
    }

    /**
     * @param StoreDocumentDto $documentDto
     *
     * @return Document
     */
    public static function create(StoreDocumentDto $documentDto)
    {
        /** @var Document $doc */
        $doc = null;

        DB::transaction(static function () use ($documentDto, &$doc) {
            /** @var BondDocument $documentable */
            $documentable = new BondDocument([
                'bond_id' => $documentDto->referentId,
            ]);

            $documentable->save();

            /** @var Document $document */
            $document = new Document([
                'original_name' => $documentDto->fileName,
                'document_type_id' => $documentDto->documentTypeId,
                'documentable_type' => BondDocument::class,
                'documentable_id' => $documentDto->referentId,
                'file_data' => $documentDto->fileData,
            ]);

            $documentable->document()->save($document);

            $doc = $document;
        });

        return $doc;
    }
}
