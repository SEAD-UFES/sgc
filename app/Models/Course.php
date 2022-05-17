<?php

namespace App\Models;

use App\ModelFilters\CourseFilter;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Course extends Model
{
    use HasFactory;
    use Sortable;
    use CourseFilter, Filterable;

    public $sortable = [
        'id',
        'name',
        'description',
        'begin',
        'end',
        'created_at',
        'updated_at',
    ];
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

    protected $fillable = [
        'name',
        'description',
        'course_type_id',
        'begin',
        'end',
    ];

    protected $observables = [
        'listed',
        'fetched',
    ];

    private static $whiteListFilter = ['*'];

    public function courseType()
    {
        return $this->belongsTo(CourseType::class);
    }

    public function employees()
    {
        return $this
            ->belongsToMany(Employee::class, 'bonds')
            ->withPivot('course_id', 'employee_id', 'role_id', 'pole_id', /* 'classroom_id',*/ 'begin', 'end', 'terminated_at', 'volunteer', 'impediment', 'impediment_description', 'uaba_checked_at')
            ->using(Bond::class)->as('bond')
            ->withTimestamps();
    }

    public function approveds()
    {
        return $this->hasMany(Approved::class);
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
