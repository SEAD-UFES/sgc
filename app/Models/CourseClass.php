<?php

namespace App\Models;

use App\Models\Filters\CourseClassFilter;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Kyslik\ColumnSortable\Sortable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class CourseClass extends Model
{
    use HasFactory;
    use Sortable;
    use CourseClassFilter;
    use Filterable;
    use LogsActivity;

    /**
     * @var bool
     */
    public $incrementing = true;

    /**
     * @var string
     */
    protected $table = 'course_classes';

    /**
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'course_id',
        'code',
        'name',
        'cpp',
    ];

    /**
     * @var array<int, string>
     */
    public static $sortable = [
        'id',
        'course_id',
        'code',
        'name',
        'cpp',
        'created_at',
        'updated_at',
    ];

    /**
     * @var array<int, string>
     */
    public static $acceptedFilters = [
        'courseNameContains',
        'codeContains',
        'nameContains',
        'cppContains',
    ];

    /**
     * @var array<int, string>
     *
     * @phpstan-ignore-next-line
     */
    private static $whiteListFilter = ['*'];

    /**
     * @return HasMany<Bond>
     */
    public function bonds(): HasMany
    {
        return $this->hasMany(Bond::class, 'course_class_id');
    }

    /**
     * @return BelongsTo<Course, CourseClass>
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id');
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
