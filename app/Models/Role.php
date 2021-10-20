<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use App\ModelFilters\roleFilter;

class Role extends Model
{
    use HasFactory;
    use Sortable;
    use roleFilter, Filterable;


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

    public $sortable = ['id', 'name', 'description', 'grant_value', 'created_at', 'updated_at'];

    private static $whiteListFilter = ['*'];
    public static $accepted_filters = [
        'name_contains',
        'description_contains',
        'grantvalue_exactly',
        'grantvalue_BigOrEqu',
        'grantvalue_LowOrEqu',
        'grantType_name_contains'
    ];

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
}
