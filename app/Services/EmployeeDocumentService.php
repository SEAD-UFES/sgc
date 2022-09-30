<?php

namespace App\Services;

use App\Events\EmployeeDocumentExported;
use App\Events\ModelListed;
use App\Interfaces\EmployeeDocumentRepositoryInterface;
use App\Models\Document;
use App\Models\Employee;
use App\Models\EmployeeDocument;
use App\Services\Dto\StoreDocumentDto;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class EmployeeDocumentService extends GenericDocumentService
{
    public function __construct(private EmployeeDocumentRepositoryInterface $repository)
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
        ModelListed::dispatch(EmployeeDocument::class);

        return $this->repository::all(sort: $sort, direction: $direction);
    }

    /**
     * Undocumented function
     *
     * @param StoreDocumentDto $storeEmployeeDocumentDto
     *
     * @return ?Document
     */
    public function create(StoreDocumentDto $storeEmployeeDocumentDto): ?Document
    {
        $document = null;

        DB::transaction(function () use ($storeEmployeeDocumentDto, &$document) {
            $oldDocuments = $this->repository
                ->getByEmployeeIdOfTypeId(
                    (int) $storeEmployeeDocumentDto->referentId,
                    (int) $storeEmployeeDocumentDto->documentTypeId
                );

            foreach ($oldDocuments as $oldDocument) {
                $this->repository->delete($oldDocument->id);
            }

            $document = $this->repository->create($storeEmployeeDocumentDto);
        });

        return $document;
    }

    /**
     * Undocumented function
     *
     * @param Employee $employee
     * @param string $zipFileName
     *
     * @return string
     */
    public function zipDocuments(Employee $employee, ?string $zipFileName = null): string
    {
        $employeeDocuments = $this->repository->getByEmployeeId($employee->id);

        $zipFileName = $zipFileName ?? date('Y-m-d') . '_' . $employee->name . '.zip';

        EmployeeDocumentExported::dispatch($employee);

        return parent::zipGenericDocuments($employeeDocuments, $zipFileName);
    }
}
