<?php

namespace App\Models;

use App\Enums\States;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Identity extends Model
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
    protected $table = 'identities';

    /**
     * @var string
     */
    protected $primaryKey = 'employee_id';

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'employee_id',
        'type_id',
        'number',
        'issue_date',
        'issuer',
        'issuer_state',
    ];

    // ==================== Casts ====================

    protected $casts = [
        'issuer_state' => States::class,
    ];

    // ==================== Relationships ====================

    /**
     * @return BelongsTo<Employee, Identity>
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }

    /**
     * @return BelongsTo<DocumentType, Identity>
     */
    public function type(): BelongsTo
    {
        return $this->belongsTo(DocumentType::class, 'type_id', 'id');
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
