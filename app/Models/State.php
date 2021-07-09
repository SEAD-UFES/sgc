<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uf',
        'name',
        'ibge_uf_code',
    ];

    public function employee()
    {
        return $this->hasMany(Employee::class);
    }

    public function employeeBirth()
    {
        return $this->hasMany(Employee::class);
    }
}
