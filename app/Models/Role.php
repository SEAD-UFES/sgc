<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Role extends Model
{
    use HasFactory;
    use Sortable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'grant_value',
    ];

    public $sortable = ['id', 'name', 'description', 'grant_value', 'created_at', 'updated_at'];

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
