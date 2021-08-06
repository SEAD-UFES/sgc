<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use App\ModelFilters\poleFilter;

class Pole extends Model
{
    use HasFactory;
    use Sortable;
    use poleFilter, Filterable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
    ];

    private static $whiteListFilter = [''];
    public static $accepted_filters = [
        'name_contains'
    ];

    public $sortable = ['id', 'name', 'description', 'created_at', 'updated_at'];

    public function bonds()
    {
        return $this->hasMany(Bond::class);
    }

    public function approveds()
    {
        return $this->hasMany(Approved::class);
    }
}
