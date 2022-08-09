<?php

namespace App\Models;

use App\Enums\KnowledgeAreas;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Qualification extends Model
{
    use HasFactory;

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
}
