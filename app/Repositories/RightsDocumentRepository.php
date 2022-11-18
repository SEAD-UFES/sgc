<?php

namespace App\Repositories;

use App\Helpers\ModelSortValidator;
use App\Models\Bond;
use App\Models\Document;
use App\Models\DocumentType;
use App\Models\Impediment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class RightsDocumentRepository
{
    /**
     * Undocumented function
     *
     * @param ?string $direction
     * @param ?string $sort
     *
     * @return LengthAwarePaginator<Document>
     */
    public static function all(?string $direction, ?string $sort): LengthAwarePaginator
    {
        $sort = ModelSortValidator::validateSort(Document::class, $sort);
        $direction = ModelSortValidator::validateDirection($direction);

        // TODO: Think a better way to do this
        /** @var int $rightsTypeId */
        $rightsTypeId = DocumentType::select('id')
            ->where('name', 'Termo de cessão de direitos')
            ->first()?->getAttribute('id');

        /** @var Builder<Document> $query */
        $query = Document::select([
            'documents.id',
            'documents.file_name',

            'document_types.name as document_type',

            'bonds.id as bond_id',

            'roles.name as role_name',
            'courses.name as course_name',
            'poles.name AS pole_name',

            'employees.id as employee_id',
            'employees.name as employee_name',
        ])
            ->whereHasMorph('related', Bond::class)
            ->where('documents.document_type_id', $rightsTypeId)
            ->whereNotIn('bonds.id', Impediment::select('bond_id')->whereNull('closed_by_id'))

            ->join('document_types', 'documents.document_type_id', '=', 'document_types.id')
            ->join('bonds', 'documents.related_id', '=', 'bonds.id')
            ->join('employees', 'bonds.employee_id', '=', 'employees.id')
            ->join('roles', 'bonds.role_id', '=', 'roles.id')
            ->leftJoin('bond_course', 'bonds.id', '=', 'bond_course.bond_id')
            ->leftJoin('courses', 'bond_course.course_id', '=', 'courses.id')
            ->leftJoin('bond_pole', 'bonds.id', '=', 'bond_pole.bond_id')
            ->leftJoin('poles', 'bond_pole.pole_id', '=', 'poles.id')

            ->AcceptRequest(Document::class::$acceptedFilters)->filter() // AcceptRequest: mehdi-fathi/eloquent-filter
            ->sortable()
            ->orderBy('documents.id', 'desc');

        /** @var LengthAwarePaginator<Document> $documents */
        $documents = $query->paginate(10);

        // AbstractPaginator->withQueryString():
        // append all of the current request's query string values to the pagination links
        // [https://laravel.com/docs/9.x/pagination]
        $documents->withQueryString();

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
}
