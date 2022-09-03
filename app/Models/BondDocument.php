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
class BondDocument extends Model
{
    use HasFactory;
    use Sortable;
    use LogsActivity;

    /**
     * @const string
     */
    public const REFERENT_ID = 'bond_id';

    /**
     * @var array<int, string>
     */
    public static $sortable = [
        'id',
        'original_name',
        'document_type',
        'created_at',
        'updated_at',
        'course_name',
        'employee_name',
        'role_name',
        'pole_name',
    ];

    /**
     * @var array<int, string>
     */
    public static $accepted_filters = [
        'originalnameContains',
        'documentTypeNameContains',
        'bond',
        'bondEmployeeNameContains',
        'bondRoleNameContains',
        'bondPoleNameContains',
        'bondCourseNameContains',
    ];

    /**
     * @var string
     */
    protected $table = 'bond_documents';

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'bond_id',
    ];

    /**
     * @return MorphOne<Document>
     */
    public function document(): MorphOne
    {
        return $this->morphOne(Document::class, 'documentable');
    }

    /**
     * @return BelongsTo<Bond, BondDocument>
     */
    public function bond(): BelongsTo
    {
        return $this->belongsTo(Bond::class, 'bond_id');
    }

    /**
     * @param Builder<BondDocument> $query
     * @param string $direction
     *
     * @return Builder<BondDocument>
     */
    //metodo de ordenação para (bond->employee->name) no sortable
    public function bondEmployeeNameSortable($query, $direction): Builder
    {
        return $query
            ->join('bonds', 'bond_documents.bond_id', '=', 'bonds.id')
            ->join('employees', 'bonds.employee_id', '=', 'employees.id')
            ->orderBy('employees.name', $direction)
            ->select('bond_documents.*');
    }

    /**
     * @param Builder<BondDocument> $query
     * @param string $direction
     *
     * @return Builder<BondDocument>
     */
    //metodo de ordenação para (bond->role->name) no sortable
    public function bondRoleNameSortable($query, $direction)
    {
        return $query
            ->join('bonds', 'bond_documents.bond_id', '=', 'bonds.id')
            ->join('roles', 'bonds.role_id', '=', 'roles.id')
            ->orderBy('roles.name', $direction)
            ->select('bond_documents.*');
    }

    /**
     * @param Builder<BondDocument> $query
     * @param string $direction
     *
     * @return Builder<BondDocument>
     */
    //metodo de ordenação para (bond->course->name) no sortable
    public function bondCourseNameSortable($query, $direction)
    {
        return $query
            ->join('bonds', 'bond_documents.bond_id', '=', 'bonds.id')
            ->join('courses', 'bonds.course_id', '=', 'courses.id')
            ->orderBy('courses.name', $direction)
            ->select('bond_documents.*');
    }

    /**
     * @param Builder<BondDocument> $query
     * @param string $direction
     *
     * @return Builder<BondDocument>
     */
    //metodo de ordenação para (bond->pole->name) no sortable
    public function bondPoleNameSortable($query, $direction)
    {
        return $query
            ->join('bonds', 'bond_documents.bond_id', '=', 'bonds.id')
            ->join('poles', 'bonds.course_id', '=', 'poles.id')
            ->orderBy('poles.name', $direction)
            ->select('bond_documents.*');
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

                'bonds.id AS bond_id',
                'bonds.course_id',
                'courses.name AS course_name',
                'bonds.employee_id',
                'employees.name AS employee_name',
                'bonds.role_id',
                'roles.name AS role_name',
                'bonds.pole_id',
                'poles.name AS pole_name',
            ]
        )
            ->whereHasMorph('documentable', \App\Models\BondDocument::class)
            ->join('document_types', 'document_types.id', '=', 'documents.document_type_id')
            ->join('bond_documents', 'bond_documents.id', '=', 'documentable_id')
            ->join('bonds', 'bonds.id', '=', 'bond_documents.bond_id')
            ->join('courses', 'courses.id', '=', 'bonds.course_id')
            ->join('roles', 'roles.id', '=', 'bonds.role_id')
            ->join('poles', 'poles.id', '=', 'bonds.pole_id')
            ->join('employees', 'employees.id', '=', 'bonds.employee_id');
    }

    /**
     * @return Builder<Document>
     */
    public function queryRights(): Builder
    {
        $documentType = DocumentType::where('name', 'Ficha de Inscrição - Termos e Licença')->first();

        return $this->query = $this->queryDocuments()
            ->select(
                [
                    'documents.id',
                    'documents.original_name',
                    'documents.document_type_id',
                    'document_types.name AS document_type',
                    'documents.created_at',
                    'documents.updated_at',

                    'bonds.id AS bond_id',
                    'bonds.course_id',
                    'courses.name AS course_name',
                    'bonds.employee_id',
                    'employees.name AS employee_name',
                    'bonds.role_id',
                    'roles.name AS role_name',
                    'bonds.pole_id',
                    'poles.name AS pole_name',

                    'bonds.uaba_checked_at',
                    'bonds.impediment',
                ]
            )
            ->where('documents.document_type_id', $documentType?->id)
            ->whereNotNull('uaba_checked_at')
            ->where('impediment', false);
    }

    /**
     * @return string
     */
    public static function referentId(): string
    {
        return self::REFERENT_ID;
    }

    public function employee(): Employee
    {
        /**
         * @var Bond $bond
         */
        $bond = $this->bond;

        /**
         * @var Employee $employee
         */
        $employee = $bond->employee;

        return $employee;
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
