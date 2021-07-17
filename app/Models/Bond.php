<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Bond extends Pivot
{
    use HasFactory;

    public $incrementing = true;
    
    protected $table = 'bonds';

    protected $fillable = [
        /* 'course_id',
        'employee_id',
        'role_id',
        'pole_id', */
        //'classroom_id',
        'begin',
        'end',
        'terminated_on',
        'volunteer',
        'impediment',
        'uaba_checked_on',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function pole()
    {
        return $this->belongsTo(Pole::class);
    }

    /* public function classroom()
    {
        return $this->belongsToMany(Course::class);
    } */

    public function bondDocuments()
    {
        return $this->hasMany(BondDocument::class, 'bond_id');
    }
}
