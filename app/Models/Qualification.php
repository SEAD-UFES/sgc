<?php

namespace App\Models;

use App\Enums\KnowledgeAreas;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Qualification extends Model
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
    protected $table = 'qualifications';

    /**
     * @var string
     */
    protected $primaryKey = 'bond_id';

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'bond_id',
        'knowledge_area',
        'course_name',
        'institution_name',
    ];

    // ==================== Casts ====================

    protected $casts = [
        'knowledge_area' => KnowledgeAreas::class,
    ];

    // ==================== Relationships ====================

    /**
     * @return BelongsTo<Bond, Qualification>
     */
    public function bond(): BelongsTo
    {
        return $this->belongsTo(Bond::class, 'bond_id', 'id');
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
