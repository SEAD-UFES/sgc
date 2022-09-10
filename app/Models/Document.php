<?php

namespace App\Models;

use App\ModelFilters\DocumentFilter;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Kyslik\ColumnSortable\Sortable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * App\Models\Document
 *
 * @property string $file_data
 */
class Document extends Model
{
    use HasFactory;
    use Sortable;
    use DocumentFilter;
    use Filterable;
    use LogsActivity;

    /**
     * @var array<int, string>
     */
    public $sortable = [
        'id',
        'original_name',
        'created_at',
        'updated_at',
    ];

    protected $table = 'documents';

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'original_name',
        'document_type_id',
        'documentable_type',
        'documentable_id',
        'file_data',
    ];

    /**
     * @var array<int, string>
     *
     * @phpstan-ignore-next-line
     */
    private static $whiteListFilter = ['*'];

    /**
     * @return Employee
     */
    public function employeeModel()
    {
        /**
         * @var EmployeeDocument|BondDocument $documentable
         */
        $documentable = $this->documentable()->first();
        /**
         * @var Employee $employee
         */
        $employee = $documentable->employee()->first();

        return $employee;
    }

    /**
     * @return BelongsTo<EmployeeDocument, Document>
     */
    public function employeeDocument(): BelongsTo
    {
        return $this->belongsTo(EmployeeDocument::class, 'documentable_id');
    }

    /**
     * @return BelongsTo<DocumentType, Document>
     */
    public function documentType(): BelongsTo
    {
        return $this->belongsTo(DocumentType::class);
    }

    /**
     * @return MorphTo<Model, Document>
     */
    public function documentable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * @param string $employeeId
     *
     * @return Builder<Document>
     */
    public static function employeeDocumentsByEmployeeId($employeeId): Builder
    {
        return Document::whereHasMorph('documentable', \App\Models\EmployeeDocument::class, static function ($query) use ($employeeId) {
            $query->where('employee_documents.employee_id', $employeeId);
        });
    }

    /**
     * @param string $bondId
     *
     * @return Builder<Document>
     */
    public static function bondDocumentsByBondId($bondId): Builder
    {
        return Document::whereHasMorph('documentable', \App\Models\BondDocument::class, static function ($query) use ($bondId) {
            $query->where('bond_documents.bond_id', $bondId);
        });
    }

    /**
     * @param string $bondId
     *
     * @return Builder<Document>
     */
    public static function rightsDocumentsByBondId($bondId): Builder
    {
        $documentType = DocumentType::where('name', 'Ficha de Inscrição - Termos e Licença')->first();

        return Document::where('documents.document_type_id', $documentType?->id)
            ->whereHasMorph('documentable', \App\Models\BondDocument::class, static function ($query) use ($bondId) {
                $query->where('bond_documents.bond_id', $bondId);
            });
    }

    /**
     * @return Builder<Document>
     */
    public static function rightsWithBond(): Builder
    {
        $documentType = DocumentType::where('name', 'Ficha de Inscrição - Termos e Licença')->first();

        return Document::where('documents.document_type_id', $documentType?->id)
            ->whereHasMorph('documentable', \App\Models\BondDocument::class, static function ($query) {
                $query->whereHas('bond', static function ($bondQuery) {
                    $bondQuery->whereNotNull('uaba_checked_at')->where('impediment', false);
                });
            })->with('documentable.bond');
    }

    /**
     * @return bool
     */
    public function isRights(): bool
    {
        $rightsTypeId = DocumentType::where('name', 'Ficha de Inscrição - Termos e Licença')->first()?->id;

        return $this->document_type_id === $rightsTypeId;
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
