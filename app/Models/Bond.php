<?php

namespace App\Models;

use App\ModelFilters\BondFilter;
use Carbon\Carbon;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Kyslik\ColumnSortable\Sortable;

class Bond extends Pivot
{
    use HasFactory;
    use Sortable;
    use BondFilter, Filterable;

    /**
     * @var true
     */
    public $incrementing = true;

    /**
     * @var array<int, string>
     */
    public $sortable = [
        'id',
        'begin',
        'end',
        'volunteer',
        'impediment',
        'created_at',
        'updated_at',
    ];

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

    /**
     * @var string
     */
    protected $table = 'bonds';

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'course_id',
        'employee_id',
        'role_id',
        'pole_id',
        //'classroom_id',
        'begin',
        'end',
        'terminated_at',
        'volunteer',
        'impediment',
        'impediment_description',
        'uaba_checked_at',
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
     * @return BelongsTo<Course, Bond>
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

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
     * @return BelongsTo<Pole, Bond>
     */
    public function pole(): BelongsTo
    {
        return $this->belongsTo(Pole::class);
    }

    /* public function classroom()
    {
        return $this->belongsToMany(Course::class);
    } */

    /**
     * @return HasMany<BondDocument>
     */
    public function bondDocuments(): HasMany
    {
        return $this->hasMany(BondDocument::class, 'bond_id');
    }

    /**
     * @param Builder<Bond> $query
     *
     * @return Builder<Bond>
     */
    public function scopeInActivePeriod($query): Builder
    {
        return $query
            ->where(
                static function ($query) {
                    $query
                        ->where([
                            ['bonds.begin', '<=', Carbon::today()->toDateString()],
                            ['bonds.end', '>=', Carbon::today()->toDateString()],
                        ])
                        ->orWhere([
                            ['bonds.begin', '<=', Carbon::today()->toDateString()],
                            ['bonds.end', '=', null],
                        ]);
                }
            );
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
