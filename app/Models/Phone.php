<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Phone extends Model
{
    use HasFactory;
    use LogsActivity;

    /**
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var string
     */
    protected $table = 'phones';

    /**
     * @var string
     */
    protected $primaryKey = 'employee_id';

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'employee_id',
        'area_code',
        'number',
        'type',
    ];

    // ==================== Relationships ====================

    /**
     * @return BelongsTo<Employee, Phone>
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
