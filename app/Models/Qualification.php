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
     * @var array<int, string>
     */
    protected $fillable = [
        'knowledge_area',
        'course_name',
        'institution_name',
        'bond_id',
    ];

    protected $casts = [
        'knowledge_area' => KnowledgeAreas::class,
    ];

    /**
     * @return BelongsTo<Bond, Qualification>
     */
    public function bond(): BelongsTo
    {
        return $this->belongsTo(Bond::class);
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
