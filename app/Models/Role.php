<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

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

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function grantType()
    {
        return $this->belongsTo(GrantType::class);
    }
}
