<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Kyslik\ColumnSortable\Sortable;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use App\ModelFilters\BondFilter;
use Carbon\Carbon;

class Bond extends Pivot
{
    use HasFactory;
    use Sortable;
    use BondFilter, Filterable;

    public $incrementing = true;

    protected $table = 'bonds';

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

    protected $observables = [
        'listed',
        'fetched',
    ];

    public $sortable = [
        'id',
        'begin',
        'end',
        'volunteer',
        'impediment',
        'created_at',
        'updated_at'
    ];

    private static $whiteListFilter = ['*'];
    public static $accepted_filters = [
        'employeeCpfContains',
        'employeeNameContains',
        'roleNameContains',
        'courseNameContains',
        'poleNameContains',
        'volunteerExactly',
        'impedimentExactly'
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function pole()
    {
        return $this->belongsTo(Pole::class);
    }

    /* public function classroom()
    {
        return $this->belongsToMany(Course::class);
    } */

    public function bondDocuments()
    {
        return $this->hasMany(BondDocument::class, 'bond_id');
    }

    public function scopeInActivePeriod($query)
    {
        return $query
            ->where(
                function ($query) {
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

    public function logListed()
    {
        $this->fireModelEvent('listed', false);
    }

    public function logFetched()
    {
        $this->fireModelEvent('fetched', false);
    }
}
