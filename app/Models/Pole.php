<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Pole extends Model
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
