<?php

namespace App\Models;

use App\ModelFilters\BondFilter;
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
     * @var string
     */
    protected $table = 'bonds';

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
        'employee_id',
        'role_id',
        'volunteer',
        'hiring_process',
        'begin',
        'terminated_at',
    ];

    // /**
    //  * @var array<int, string>
    //  */
    // public $sortable = [
    //     'id',
    //     'volunteer',
    //     'begin',
    //     'terminated_at',
    //     'created_at',
    //     'updated_at',
    // ];

    /**
     * @var array<int, string>
     */
    public static $accepted_filters = [
        'employeeCpfContains',
        'employeeNameContains',
        'roleNameContains',
        'courseNameContains',
        'poleNameContains',
        'volunteerExactly',
        'impedimentExactly',
    ];

    // /**
    //  * @var array<int, string>
    //  *
    //  * @phpstan-ignore-next-line
    //  */
    // private static $whiteListFilter = ['*'];

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

    // ========================================================

    // /**
    //  * @return HasMany<Document>
    //  */
    // public function rightsDocuments(): HasMany
    // {
    //     return $this->documents()
    //         ->join('documents', 'documents.related_id', '=', 'bond_documents.id')
    //         ->join('document_types', 'document_types.id', '=', 'documents.document_type_id')
    //         ->where('related_type', Document::class)
    //         ->where('document_types.name', 'like', '%Termos e Licença%');
    // }

    // /**
    //  * @return bool
    //  */
    // public function hasRightsDocuments(): bool
    // {
    //     return $this->rightsDocuments()->count() > 0;
    // }

    // /**
    //  * @param Builder<Bond> $query
    //  *
    //  * @return Builder<Bond>
    //  */
    // public function scopeActive($query): Builder
    // {
    //     return $query->where('bonds.begin', '<=', Carbon::today()->toDateString())
    //         ->where(
    //             static function ($query) {
    //                 $query->where('bonds.end', '>=', Carbon::today()->toDateString())
    //                     ->orWhereNull('bonds.end');
    //             }
    //         );
    // }

    // /**
    //  * @param Builder<Bond> $query
    //  * @param bool $status
    //  *
    //  * @return Builder<Bond>
    //  */
    // public function scopeImpededStatus(Builder $query, bool $status): Builder
    // {
    //     return $query->where('bonds.impediment', $status);
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
