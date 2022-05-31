<?php

namespace App\Models;

use App\ModelFilters\RoleFilter;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Role extends Model
{
    use HasFactory;
    use Sortable;
    use RoleFilter, Filterable;

    public $sortable = ['id', 'name', 'description', 'grant_value', 'created_at', 'updated_at'];
    public static $accepted_filters = [
        'nameContains',
        'descriptionContains',
        'grantvalueExactly',
        'grantvalueBigOrEqu',
        'grantvalueLowOrEqu',
        'grantTypeNameContains',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'grant_value',
        'grant_type_id',
    ];

    protected $observables = [
        'listed',
        'fetched',
    ];

    private static $whiteListFilter = ['*'];

    /* public function bonds()
    {
        return $this->hasMany(User::class);
    } */

    public function grantType()
    {
        return $this->belongsTo(GrantType::class);
    }

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

    protected function grantValueReal(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->grant_value / 100,
        );
    }
}
