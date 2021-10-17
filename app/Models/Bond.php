<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Kyslik\ColumnSortable\Sortable;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use App\ModelFilters\bondFilter;
use Carbon\Carbon;

class Bond extends Pivot
{
    use HasFactory;
    use Sortable;
    use bondFilter, Filterable;

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
        'employee_cpf_contains',
        'employee_name_contains',
        'role_name_contains',
        'course_name_contains',
        'pole_name_contains',
        'volunteer_exactly',
        'impediment_exactly'
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
}
