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

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*'])
            ->logExcept(['updated_at'])
            ->dontLogIfAttributesChangedOnly(['updated_at'])
            ->logOnlyDirty();
    }
}
