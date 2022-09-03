<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Kyslik\ColumnSortable\Sortable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property Builder $query
 */
class EmployeeDocument extends Model
{
    use HasFactory;
    use Sortable;
    use LogsActivity;

    /**
     * @const string
     */
    public const REFERENT_ID = 'employee_id';

    /**
     * @var array<int, string>
     */
    public static $sortable = [
        'id',
        'original_name',
        'document_type',
        'created_at',
        'updated_at',
        'employee_name',
        'employee_cpf',
    ];

    /**
     * @var array<int, string>
     */
    public static $accepted_filters = [
        'originalnameContains',
        'documentTypeNameContains',
        'employeeNameContains',
        'employeeCpfContains',
    ];

    /**
     * @var string
     */
    protected $table = 'employee_documents';

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'employee_id',
    ];

    /**
     * @return MorphOne<Document>
     */
    public function document(): MorphOne
    {
        return $this->morphOne(Document::class, 'documentable');
    }

    /**
     * @return BelongsTo<Employee, EmployeeDocument>
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * @return Builder<Document>
     */
    public function queryDocuments(): Builder
    {
        return $this->query = Document::select(
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
            ->whereHasMorph('documentable', \App\Models\EmployeeDocument::class)
            ->join('document_types', 'document_types.id', '=', 'documents.document_type_id')
            ->join('employee_documents', 'employee_documents.id', '=', 'documentable_id')
            ->join('employees', 'employees.id', '=', 'employee_documents.employee_id');
    }

    /**
     * @return string
     */
    public static function referentId(): string
    {
        return self::REFERENT_ID;
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*'])
            ->logExcept(['updated_at'])
            ->dontLogIfAttributesChangedOnly(['updated_at'])
            ->logOnlyDirty();
    }
}
