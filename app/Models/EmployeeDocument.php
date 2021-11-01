<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class EmployeeDocument extends Model
{
    use HasFactory;
    use Sortable;

    protected $table = 'employee_documents';

    const REFERENT_ID = 'employee_id';

    protected $fillable = [
        'employee_id',
    ];

    protected $observables = [
        'listed',
        'fetched',
    ];

    public static $sortable = [
        'id',
        'original_name',
        'document_type',
        'created_at',
        'updated_at',

        'employee_name',
        'employee_cpf',
    ];

    public static $accepted_filters = [
        'originalname_contains',
        'documentType_name_contains',
        'employee_name_contains',
        'employee_cpf_contains',
    ];

    public function document()
    {
        return $this->morphOne(Document::class, 'documentable');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function logListed()
    {
        $this->fireModelEvent('listed', false);
    }

    public function logFetched()
    {
        $this->fireModelEvent('fetched', false);
    }

    public function queryDocuments()
    {
        $this->query = Document::select(
            [
                'documents.id',
                'documents.original_name',
                'documents.document_type_id',
                'document_types.name AS document_type',
                'documents.created_at',
                'documents.updated_at',

                'employees.id AS employee_id',
                'employees.name AS employee_name',
                'employees.cpf AS employee_cpf',
            ]
        )
            ->whereHasMorph('documentable', 'App\Models\EmployeeDocument')
            ->join('document_types', 'document_types.id', '=', 'documents.document_type_id')
            ->join('employee_documents', 'employee_documents.id', '=', 'documentable_id')
            ->join('employees', 'employees.id', '=', 'employee_documents.employee_id');

        return $this->query;
    }
}
