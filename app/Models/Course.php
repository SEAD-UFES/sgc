<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'begin',
        'end',
    ];

    public function courseType()
    {
        return $this->belongsTo(CourseType::class);
    }

    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'bonds')->withPivot('course_id', 'employee_id', 'role_id', 'pole_id', /* 'classroom_id',*/ 'begin', 'end', 'terminated_on', 'volunteer', 'impediment', 'impediment_description', 'uaba_checked_on',)->using(Bond::class)->as('bond')->withTimestamps();
    }

    public function approveds()
    {
        return $this->hasMany(Approved::class);
    }
}
