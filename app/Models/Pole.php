<?php

namespace App\Models;

use App\ModelFilters\PoleFilter;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Kyslik\ColumnSortable\Sortable;

class Pole extends Model
{
    use HasFactory;
    use Sortable;
    use PoleFilter, Filterable;

    /**
     * @var array<int, string>
     */
    public static $accepted_filters = [
        'nameContains',
        'descriptionContains',
    ];

    /**
     * @var array<int, string>
     */
    public $sortable = ['id', 'name', 'description', 'created_at', 'updated_at'];

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
    private static $whiteListFilter = [''];

    /**
     * @return HasMany<Bond>
     */
    public function bonds(): HasMany
    {
        return $this->hasMany(Bond::class);
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
