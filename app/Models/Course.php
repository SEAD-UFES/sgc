<?php

namespace App\Models;

use App\Enums\Degrees;
use App\ModelFilters\CourseFilter;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
     * @var string
     */
    protected $table = 'courses';

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
        'name',
        'description',
        'degree',
        'lms_url',
    ];

    // /**
    //  * @var array<int, string>
    //  */
    // public $sortable = [
    //     'id',
    //     'name',
    //     'description',
    //     'created_at',
    //     'updated_at',
    // ];

    /**
     * @var array<int, string>
     */
    public static $accepted_filters = [
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

    // /**
    //  * @var array<int, string>
    //  *
    //  * @phpstan-ignore-next-line
    //  */
    // private static $whiteListFilter = ['*'];

    // ==================== Casts ====================

    protected $casts = [
        'degree' => Degrees::class,
    ];

    // ==================== Relationships ====================

    /**
     * @return BelongsToMany<Bond, Course>
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

    // /**
    //  * @return BelongsToMany<Employee>
    //  */
    // public function employees(): BelongsToMany
    // {
    //     return $this
    //         ->belongsToMany(Employee::class, 'bonds')
    //         ->withPivot('course_id', 'employee_id', 'role_id', 'pole_id', /* 'classroom_id',*/ 'begin', 'end', 'terminated_at', 'volunteer', 'impediment', 'impediment_description', 'uaba_checked_at')
    //         ->using(Bond::class)->as('bond')
    //         ->withTimestamps();
    // }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*'])
            ->logExcept(['updated_at'])
            ->dontLogIfAttributesChangedOnly(['updated_at'])
            ->logOnlyDirty();
    }
}
