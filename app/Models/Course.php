<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use App\ModelFilters\courseFilter;

class Course extends Model
{
    use HasFactory;
    use Sortable;
    use courseFilter, Filterable;

    protected $fillable = [
        'name',
        'description',
        'begin',
        'end',
    ];

    public $sortable = [
        'id',
        'name',
        'description',
        'begin',
        'end',
        'created_at',
        'updated_at'
    ];

    private static $whiteListFilter = ['*'];
    public static $accepted_filters = [
        'name_contains',
        'description_contains',
        'courseType_name_contains',
        'begin_exactly',
        'begin_BigOrEqu',
        'begin_LowOrEqu',
        'end_exactly',
        'end_BigOrEqu',
        'end_LowOrEqu'
    ];

    public function courseType()
    {
        return $this->belongsTo(CourseType::class);
    }

    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'bonds')->withPivot('course_id', 'employee_id', 'role_id', 'pole_id', /* 'classroom_id',*/ 'begin', 'end', 'terminated_on', 'volunteer', 'impediment', 'impediment_description', 'uaba_checked_on',)->using(Bond::class)->as('bond')->withTimestamps();
    }

    public function approveds()
    {
        return $this->hasMany(Approved::class);
    }
}
