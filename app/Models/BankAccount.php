<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class BankAccount extends Model
{
    use HasFactory;
    use LogsActivity;

    /**
     * @var string
     */
    protected $table = 'bank_accounts';

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
        'bank_name',
        'agency_number',
        'account',
    ];

    // ==================== Relationships ====================

    /**
     * @return BelongsTo<Employee, BankAccount>
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
