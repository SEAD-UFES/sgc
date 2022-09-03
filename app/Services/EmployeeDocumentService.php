<?php

namespace App\Services;

use App\Events\EmployeeDocumentExported;
use App\Models\Employee;
use App\Models\EmployeeDocument;
use Illuminate\Database\Eloquent\Collection;

class EmployeeDocumentService extends DocumentService
{
    public function __construct()
    {
        parent::__construct(EmployeeDocument::class, Employee::class);
    }

    /**
     * Undocumented function
     *
     * @param Employee $employee
     * @param string $zipFileName
     *
     * @return string
     */
    public function exportDocuments(Employee $employee, ?string $zipFileName = null): string
    {
        /**
         * @var Collection<int, EmployeeDocument> $documentables
         */
        $documentables = $employee->employeeDocuments()->get(); // <= Particular line
        $zipFileName = $zipFileName ?? date('Y-m-d') . '_' . $employee->name . '.zip'; // <= Particular line

        EmployeeDocumentExported::dispatch($employee);

        return parent::exportGenericDocuments($documentables, $zipFileName);
    }
}
