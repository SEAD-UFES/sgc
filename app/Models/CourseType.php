<?php

namespace App\Models;

use App\ModelFilters\CourseTypeFilter;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Kyslik\ColumnSortable\Sortable;

class CourseType extends Model
{
    use HasFactory;
    use Sortable;
    use CourseTypeFilter;
    use Filterable;

    /**
     * @var array<int, string>
     */
    public $sortable = [
        'id',
        'name',
        'description',
        'created_at',
        'updated_at',
    ];

    /**
     * @var array<int, string>
     */
    public static $accepted_filters = [
        'nameContains',
        'descriptionContains',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
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
     * @return HasMany<Course>
     */
    public function courses(): HasMany
    {
        return $this->hasMany(Course::class);
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
