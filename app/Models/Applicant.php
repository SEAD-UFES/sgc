<?php

namespace App\Models;

use App\Enums\CallStates;
use App\Models\Filters\ApplicantFilter;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Kyslik\ColumnSortable\Sortable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Applicant extends Model
{
    use HasFactory;
    use Sortable;
    use ApplicantFilter;
    use Filterable;
    use LogsActivity;

    /**
     * @var bool
     */
    public $incrementing = true;

    /**
     * @var array<int, string>
     */
    public static $sortable = [
        'id',
        'name',
        'email',
        'landline',
        'mobile',
        'hiring_process',
        'call_state',
        'created_at',
        'updated_at',
    ];

    /**
     * @var array<int, string>
     */
    public static $acceptedFilters = [
        'nameContains',
        'emailContains',
        'areacodeContains',
        'landlineContains',
        'mobileContains',
        'hiringprocessContains',
        'callStateLabelContains',
        'roleNameContains',
        'courseNameContains',
        'poleNameContains',
    ];

    /**
     * @var string
     */
    protected $table = 'applicants';

    /**
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'area_code',
        'landline',
        'mobile',
        'hiring_process',
        'role_id',
        'pole_id',
        'course_id',
        'call_state',
    ];

    // ==================== Casts ====================

    protected $casts = [
        'call_state' => CallStates::class,
    ];

    /**
     * @var array<int, string>
     *
     * @phpstan-ignore-next-line
     */
    private static $whiteListFilter = ['*'];

    // ==================== Relationships ====================

    /**
     * @return BelongsTo<Role, Applicant>
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * @return BelongsTo<Pole, Applicant>
     */
    public function pole(): BelongsTo
    {
        return $this->belongsTo(Pole::class);
    }

    /**
     * @return BelongsTo<Course, Applicant>
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
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
