<?php

namespace App\Repositories;

use App\Helpers\DocumentRepositoryHelper;
use App\Interfaces\EmployeeDocumentRepositoryInterface;
use App\Models\Bond;
use App\Models\Document;
use App\Models\EmployeeDocument;
use App\Services\Dto\StoreDocumentDto;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class EmployeeDocumentRepository extends GenericDocumentRepository implements EmployeeDocumentRepositoryInterface
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
        $sort = DocumentRepositoryHelper::validateSort(EmployeeDocument::class, $sort);
        $direction = DocumentRepositoryHelper::validateDirection($direction);

        /**
         * @var Builder<Document> $query
         */
        $query = Document::select([
            'employees.cpf as employee_cpf',
            'employees.name as employee_name',
            'documents.original_name',
            'document_types.name as document_type',
            'documents.id',
            'employees.id as employee_id',
        ])
            ->whereHasMorph('documentable', EmployeeDocument::class)
            ->with('documentable')
            ->join('document_types', 'documents.document_type_id', '=', 'document_types.id')
            ->join('employee_documents', 'documents.documentable_id', '=', 'employee_documents.id')
            ->join('employees', 'employee_documents.employee_id', '=', 'employees.id')
            ->AcceptRequest(EmployeeDocument::$accepted_filters)->filter() // AcceptRequest: mehdi-fathi/eloquent-filter
            ->orderBy($sort, $direction);

        $documents = $query->paginate(10);
        $documents->withQueryString(); // AbstractPaginator->withQueryString(): append all of the current request's query string values to the pagination links [https://laravel.com/docs/9.x/pagination]

        return $documents;
    }

    /**
     * @param int $employeeId
     * @param int $typeId
     *
     * @return Collection<int, Document>
     */
    public static function getByEmployeeIdOfTypeId(int $employeeId, int $typeId)
    {
        return Document::where('document_type_id', $typeId)->whereHasMorph('documentable', EmployeeDocument::class, static function ($query) use ($employeeId) {
            $query->where('employee_documents.employee_id', $employeeId);
        })->with('documentable')->orderBy('id', 'desc')->get();
    }

    /**
     * @param int $employeeId
     *
     * @return Collection<int, Document>
     */
    public static function getByEmployeeId(int $employeeId)
    {
        return Document::whereHasMorph('documentable', EmployeeDocument::class, static function ($query) use ($employeeId) {
            $query->where('employee_documents.employee_id', $employeeId);
        })->with('documentable')->orderBy('id', 'desc')->get();
    }

    /**
     * @param int $bondId
     *
     * @return Collection<int, Document>
     */
    public static function getByBondId(int $bondId)
    {
        $employeeId = Bond::where('id', $bondId)->firstOrFail()->employee_id;
        return self::getByEmployeeId($employeeId);
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
            /** @var EmployeeDocument $documentable */
            $documentable = new EmployeeDocument([
                'employee_id' => $documentDto->referentId,
            ]);

            $documentable->save();

            /** @var Document $document */
            $document = new Document([
                'original_name' => $documentDto->fileName,
                'document_type_id' => $documentDto->documentTypeId,
                'documentable_type' => EmployeeDocument::class,
                'documentable_id' => $documentDto->referentId,
                'file_data' => $documentDto->fileData,
            ]);

            $documentable->document()->save($document);

            $doc = $document;
        });

        return $doc;
    }
}
