<?php

namespace App\Models;

use App\ModelFilters\PoleFilter;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Pole extends Model
{
    use HasFactory;
    use Sortable;
    use PoleFilter, Filterable;
    public static $accepted_filters = [
        'nameContains',
        'descriptionContains',
    ];

    public $sortable = ['id', 'name', 'description', 'created_at', 'updated_at'];

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

    private static $whiteListFilter = [''];

    public function bonds()
    {
        return $this->hasMany(Bond::class);
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
