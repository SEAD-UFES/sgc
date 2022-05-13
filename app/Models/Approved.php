<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use App\ModelFilters\ApprovedFilter;

class Approved extends Model
{
    use HasFactory;
    use Sortable;
    use ApprovedFilter, Filterable;

    protected $fillable = [
        'name',
        'email',
        'area_code',
        'phone',
        'mobile',
        'announcement',
        'course_id',
        'pole_id',
        'role_id',
        'approved_state_id',
    ];

    protected $observables = [
        'listed',
        'fetched',
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
        'nameContains',
        'emailContains',
        'areacodeContains',
        'phoneContains',
        'mobileContains',
        'announcementContains',
        'approvedStateNameContains',
        'roleNameContains',
        'courseNameContains',
        'poleNameContains'
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

    public function logFetched()
    {
        $this->fireModelEvent('fetched', false);
    }
}
