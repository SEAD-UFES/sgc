<?php

namespace App\Models;

use App\Enums\Degrees;
use App\Models\Filters\CourseFilter;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Kyslik\ColumnSortable\Sortable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Course extends Model
{
    use HasFactory;
    use Sortable;
    use CourseFilter;
    use Filterable;
    use LogsActivity;

    /**
     * @var bool
     */
    public $incrementing = true;

    /**
     * @var array<int, string>
     */
    public static $sortable = [
        'id',
        'name',
        'description',
        'degree',
        'created_at',
        'updated_at',
    ];

    /**
     * @var array<int, string>
     */
    public static $acceptedFilters = [
        'nameContains',
        'descriptionContains',
        'courseTypeNameContains',
        'beginExactly',
        'beginBigOrEqu',
        'beginLowOrEqu',
        'endExactly',
        'endBigOrEqu',
        'endLowOrEqu',
    ];

    /**
     * @var string
     */
    protected $table = 'courses';

    /**
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'degree',
        'lms_url',
    ];

    // ==================== Casts ====================

    protected $casts = [
        'degree' => Degrees::class,
    ];

    /**
     * @var array<int, string>
     *
     * @phpstan-ignore-next-line
     */
    private static $whiteListFilter = ['*'];

    // ==================== Relationships ====================

    /**
     * @return BelongsToMany<Bond>
     */
    public function bonds(): BelongsToMany
    {
        return $this->belongsToMany(Bond::class, 'bond_course', 'course_id', 'bond_id');
    }

    /**
     * @return HasMany<CourseClass>
     */
    public function courseClasses(): HasMany
    {
        return $this->hasMany(CourseClass::class, 'course_id', 'id');
    }

    /**
     * @return HasMany<Applicant>
     */
    public function applicants(): HasMany
    {
        return $this->hasMany(Applicant::class, 'course_id', 'id');
    }

    /**
     * @return HasMany<Responsibility>
     */
    public function responsibilities(): HasMany
    {
        return $this->hasMany(Responsibility::class, 'course_id', 'id');
    }

    // ==============================

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*'])
            ->logExcept(['updated_at'])
            ->dontLogIfAttributesChangedOnly(['updated_at'])
            ->logOnlyDirty();
    }
}
