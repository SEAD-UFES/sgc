<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use App\ModelFilters\approvedFilter;

class Approved extends Model
{
    use HasFactory;
    use Sortable;
    use approvedFilter, Filterable;

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

    protected $observables = [
        'listed',
        'retrieved',
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

    private static $whiteListFilter = ['*'];
    public static $accepted_filters = [
        'name_contains',
        'email_contains',
        'areacode_contains',
        'phone_contains',
        'mobile_contains',
        'announcement_contains',
        'approvedState_name_contains',
        'role_name_contains',
        'course_name_contains',
        'pole_name_contains'
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

    public function logListed()
    {
        $this->fireModelEvent('listed', false);
    }

    public function logViewed()
    {
        $this->fireModelEvent('retrieved', false);
    }
}
