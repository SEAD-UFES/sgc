<?php

namespace App\Models;

use App\Enums\States;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Address extends Model
{
    use HasFactory;
    use LogsActivity;

    /**
     * @var string
     */
    protected $table = 'addresses';

    /**
     * @var string
     */
    protected $primaryKey = 'employee_id';

    /**
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'employee_id',
        'street',
        'complement',
        'number',
        'district',
        'zip_code',
        'state',
        'city',
    ];

    // ==================== Casts ====================

    protected $casts = [
        'state' => States::class,
    ];

    // ==================== Relationships ====================

    /**
     * @return BelongsTo<Employee, Address>
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
