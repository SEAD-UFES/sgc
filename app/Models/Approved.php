<?php

namespace App\Models;

use App\ModelFilters\ApprovedFilter;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Kyslik\ColumnSortable\Sortable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Approved extends Model
{
    use HasFactory;
    use Sortable;
    use ApprovedFilter;
    use Filterable;
    use LogsActivity;

    /**
     * @var array<int, string>
     */
    public $sortable = [
        'id',
        'name',
        'email',
        'area_code',
        'phone',
        'mobile',
        'announcement',
        'created_at',
        'updated_at',
    ];

    /**
     * @var array<int, string>
     */
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
        'poleNameContains',
    ];

    /**
     * @var array<int, string>
     */
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

    /**
     * @var array<int, string>
     *
     * @phpstan-ignore-next-line
     */
    private static $whiteListFilter = ['*'];

    /**
     * @return BelongsTo<ApprovedState, Approved>
     */
    public function approvedState(): BelongsTo
    {
        return $this->belongsTo(ApprovedState::class);
    }

    /**
     * @return BelongsTo<Course, Approved>
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * @return BelongsTo<Pole, Approved>
     */
    public function pole(): BelongsTo
    {
        return $this->belongsTo(Pole::class);
    }

    /**
     * @return BelongsTo<Role, Approved>
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*'])
            ->logExcept(['updated_at'])
            ->dontLogIfAttributesChangedOnly(['updated_at'])
            ->logOnlyDirty();
    }
}
