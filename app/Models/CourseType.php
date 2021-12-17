<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use App\ModelFilters\CourseTypeFilter;

class CourseType extends Model
{
    use HasFactory;
    use Sortable;
    use CourseTypeFilter, Filterable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
    ];

    protected $observables = [
        'listed',
        'fetched',
    ];

    public $sortable = [
        'id',
        'name',
        'description',
        'created_at',
        'updated_at'
    ];

    private static $whiteListFilter = ['*'];
    public static $accepted_filters = [
        'nameContains',
        'descriptionContains'
    ];

    public function courses()
    {
        return $this->hasMany(Course::class);
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
