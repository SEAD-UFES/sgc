<?php

namespace App\Models;

use App\ModelFilters\CourseTypeFilter;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class CourseType extends Model
{
    use HasFactory;
    use Sortable;
    use CourseTypeFilter, Filterable;

    public $sortable = [
        'id',
        'name',
        'description',
        'created_at',
        'updated_at',
    ];
    public static $accepted_filters = [
        'nameContains',
        'descriptionContains',
    ];

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

    private static $whiteListFilter = ['*'];

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
