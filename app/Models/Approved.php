<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Approved extends Model
{
    use HasFactory;
    use Sortable;

    protected $fillable = [
        'name',
        'email',
        'area_code',
        'phone',
        'mobile',
        'announcement',
        /* 'course_id',
        'pole_id',
        'role_id',
        'approved_state_id', */
    ];

    public $sortable = [
        'id',
        'name',
        'email',
        'area_code',
        'phone',
        'mobile',
        'announcement',
        'created_at',
        'updated_at'
    ];

    public function approvedState()
    {
        return $this->belongsTo(ApprovedState::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function pole()
    {
        return $this->belongsTo(Pole::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
