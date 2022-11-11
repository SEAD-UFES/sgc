<?php

namespace App\Repositories;

use App\Helpers\DocumentRepositoryHelper;
use App\Models\Document;
use App\Models\DocumentType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class RightsDocumentRepository
{
    /**
     * Undocumented function
     *
     * @param string $sort
     * @param string $direction
     *
     * @return LengthAwarePaginator
     */
    public static function getAllDocuments(string $sort = 'documents.id', string $direction = 'desc'): LengthAwarePaginator
    {
        $sort = DocumentRepositoryHelper::validateSort(Document::class, $sort);
        $direction = DocumentRepositoryHelper::validateDirection($direction);

        // TODO: Think a better way to do this
        $documentType = DocumentType::where('name', 'Termo de cessão de direitos')->first();

        /**
         * @var Builder<Document> $query
         */
        $query = Document::select([
            'documents.id',
            'documents.file_name',
            'documents.related_id',
            'documents.document_type_id',
            'documents.created_at',
            'documents.updated_at',

            'document_types.name AS document_type',

            'bonds.id AS bond_id',
            //'bonds.course_id',
            'bonds.employee_id',
            'bonds.role_id',
            //'bonds.pole_id',
            //'bonds.uaba_checked_at',
            //'bonds.impediment',
            'impediments.reviewer_cheked_at',

            'courses.name AS course_name',
            'roles.name AS role_name',
            'poles.name AS pole_name',
            'employees.name AS employee_name',

        ])
            ->whereHasMorph('related', Document::class)
            ->where('documents.document_type_id', $documentType?->id)
            ->whereNotNull('uaba_checked_at')
            ->where('impediment', false)

            ->join('document_types', 'documents.document_type_id', '=', 'document_types.id')
            ->join('bond_documents', 'documents.related_id', '=', 'bond_documents.id')
            ->join('bonds', 'bond_documents.bond_id', '=', 'bonds.id')
            ->join('courses', 'bonds.course_id', '=', 'courses.id')
            ->join('roles', 'bonds.role_id', '=', 'roles.id')
            ->join('poles', 'bonds.pole_id', '=', 'poles.id')
            ->join('employees', 'bonds.employee_id', '=', 'employees.id')

            ->AcceptRequest(Document::$accepted_filters)->filter() // AcceptRequest: mehdi-fathi/eloquent-filter
            ->orderBy($sort, $direction);

        $documents = $query->paginate(10);
        $documents->withQueryString(); // AbstractPaginator->withQueryString(): append all of the current request's query string values to the pagination links [https://laravel.com/docs/9.x/pagination]

        return $documents;
    }
}
