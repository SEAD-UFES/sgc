<?php

namespace App\Models;

use App\Enums\MaritalStatuses;
use App\Enums\States;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class PersonalDetail extends Model
{
    use HasFactory;
    use LogsActivity;

    /**
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var array<int, string>
     */
    public static $sortable = [
        'job',
    ];

    /**
     * @var string
     */
    protected $table = 'personal_details';

    /**
     * @var string
     */
    protected $primaryKey = 'employee_id';

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'employee_id',
        'job',
        'birth_date',
        'birth_state',
        'birth_city',
        'marital_status',
        'father_name',
        'mother_name',
    ];

    // ==================== Casts ====================

    protected $casts = [
        'birth_state' => States::class,
        'marital_status' => MaritalStatuses::class,
    ];

    // ==================== Relationships ====================

    /**
     * @return BelongsTo<Employee, PersonalDetail>
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
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
