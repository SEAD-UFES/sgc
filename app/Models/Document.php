<?php

namespace App\Models;

use App\ModelFilters\DocumentFilter;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
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
     * @var string
     */
    protected $table = 'documents';

    /**
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * @var bool
     */
    public $incrementing = true;

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'file_name',
        'related_type',
        'related_id',
        'document_type_id',
        'file_data',
    ];

    /**
     * @var array<int, string>
     */
    public static $sortable = [
        'id',
        'file_name',
        'created_at',
        'updated_at',
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

    // /**
    //  * @var array<int, string>
    //  *
    //  * @phpstan-ignore-next-line
    //  */
    // private static $whiteListFilter = ['*'];

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
    public function related(): MorphTo
    {
        return $this->morphTo();
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
