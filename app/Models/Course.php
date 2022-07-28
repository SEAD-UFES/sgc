<?php

namespace App\Models;

use App\ModelFilters\CourseFilter;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Kyslik\ColumnSortable\Sortable;

class Course extends Model
{
    use HasFactory;
    use Sortable;
    use CourseFilter;
    use Filterable;

    /**
     * @var array<int, string>
     */
    public $sortable = [
        'id',
        'name',
        'description',
        'begin',
        'end',
        'created_at',
        'updated_at',
    ];

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

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'course_type_id',
        'begin',
        'end',
    ];

    /**
     * @var array<int, string>
     */
    protected $observables = [
        'listed',
        'fetched',
    ];

    /**
     * @var array<int, string>
     */
    private static $whiteListFilter = ['*'];

    /**
     * @return BelongsTo<CourseType, Course>
     */
    public function courseType(): BelongsTo
    {
        return $this->belongsTo(CourseType::class);
    }

    /**
     * @return BelongsToMany<Employee>
     */
    public function employees(): BelongsTomAny
    {
        return $this
            ->belongsToMany(Employee::class, 'bonds')
            ->withPivot('course_id', 'employee_id', 'role_id', 'pole_id', /* 'classroom_id',*/ 'begin', 'end', 'terminated_at', 'volunteer', 'impediment', 'impediment_description', 'uaba_checked_at')
            ->using(Bond::class)->as('bond')
            ->withTimestamps();
    }

    /**
     * @return HasMany<Approved>
     */
    public function approveds(): HasMany
    {
        return $this->hasMany(Approved::class);
    }

    /**
     * @return void
     */
    public function logListed(): void
    {
        $this->fireModelEvent('listed', false);
    }

    /**
     * @return void
     */
    public function logFetched(): void
    {
        $this->fireModelEvent('fetched', false);
    }
}
