<?php

namespace App\Models;

use App\Models\Filters\BondFilter;
use Carbon\Carbon;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Kyslik\ColumnSortable\Sortable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Bond extends Pivot
{
    use HasFactory;
    use Sortable;
    use BondFilter;
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
        'volunteer',
        'created_at',
        'updated_at',
        'last_open_impediment_date',
    ];

    /**
     * @var array<int, string>
     */
    public static $acceptedFilters = [
        'employeeCpfContains',
        'employeeNameContains',
        'roleNameContains',
        'courseNameContains',
        'poleNameContains',
        'volunteerExactly',
        'impedimentExactly',
    ];

    /**
     * @var string
     */
    protected $table = 'bonds';

    /**
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'employee_id',
        'role_id',
        'volunteer',
        'hiring_process',
        'begin',
        'terminated_at',
    ];

    /**
     * @var array<int, string>
     *
     * @phpstan-ignore-next-line
     */
    private static $whiteListFilter = ['*'];

    // ==================== Accessors ====================

    /**
     * @return ?Course
     */
    public function getCourseAttribute(): ?Course
    {
        return $this->courses->first();
    }

    /**
     * @return ?CourseClass
     */
    public function getCourseClassAttribute(): ?CourseClass
    {
        return $this->courseClasses->first();
    }

    /**
     * @return ?Pole
     */
    public function getPoleAttribute(): ?Pole
    {
        return $this->poles->first();
    }

    /**
     * @return ?Carbon
     */
    public function getLastOpenImpedimentDateAttribute(): ?Carbon
    {
        return $this->impediments->whereNull('closed_at')->sortByDesc('created_at')->first()?->created_at;
    }

    // ==================== Relationships ====================

    /**
     * @return BelongsTo<Employee, Bond>
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * @return BelongsTo<Role, Bond>
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * @return HasOne<Qualification>
     */
    public function qualification(): HasOne
    {
        return $this->hasOne(Qualification::class, 'bond_id');
    }

    /**
     * @return HasMany<Impediment>
     */
    public function impediments(): HasMany
    {
        return $this->hasMany(Impediment::class, 'bond_id');
    }

    /**
     * @return BelongsToMany<Course>
     */
    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'bond_course', 'bond_id', 'course_id');
    }

    /**
     * @return BelongsToMany<CourseClass>
     */
    public function courseClasses(): BelongsToMany
    {
        return $this->belongsToMany(CourseClass::class, 'bond_course_class', 'bond_id', 'course_class_id');
    }

    /**
     * @return BelongsToMany<Pole>
     */
    public function poles(): BelongsToMany
    {
        return $this->belongsToMany(Pole::class, 'bond_pole', 'bond_id', 'pole_id');
    }

    /**
     * @return MorphMany<Document>
     */
    public function documents(): MorphMany
    {
        return $this->morphMany(Document::class, 'related');
    }

    // ==================== ColumnSortable overriding ====================

    /**
     * @param Builder<Bond> $query
     * @param string $direction
     *
     * @return Builder<Bond>
     */
    public function cpfSortable(Builder $query, string $direction): Builder
    {
        return $query->orderBy('employees.cpf', $direction);
    }

    /**
     * @param Builder<Bond> $query
     * @param string $direction
     *
     * @return Builder<Bond>
     */
    public function nameSortable(Builder $query, string $direction): Builder
    {
        return $query->orderBy('employees.name', $direction);
    }

    /**
     * @param Builder<Bond> $query
     * @param string $direction
     *
     * @return Builder<Bond>
     */
    public function roleSortable(Builder $query, string $direction): Builder
    {
        return $query->orderBy('roles.name', $direction);
    }

    /**
     * @param Builder<Bond> $query
     * @param string $direction
     *
     * @return Builder<Bond>
     */
    public function courseSortable(Builder $query, string $direction): Builder
    {
        return $query->orderBy('courses.name', $direction);
    }

    /**
     * @param Builder<Bond> $query
     * @param string $direction
     *
     * @return Builder<Bond>
     */
    public function poleSortable(Builder $query, string $direction): Builder
    {
        return $query->orderBy('poles.name', $direction);
    }

    /**
     * @param Builder<Bond> $query
     * @param string $direction
     *
     * @return Builder<Bond>
     */
    public function volunteerSortable(Builder $query, string $direction): Builder
    {
        return $query->orderBy('bonds.volunteer', $direction);
    }

    /**
     * @param Builder<Bond> $query
     * @param string $direction
     *
     * @return Builder<Bond>
     */
    public function impededSortable(Builder $query, string $direction): Builder
    {
        return $query->orderBy('last_open_impediment_date', $direction);
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
