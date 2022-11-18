<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BondCourse extends Model
{
    /**
     * @var bool
     */
    public $incrementing = false;
    /**
     * @var string
     */
    protected $table = 'bond_course';

    /**
     * @var string
     */
    protected $primaryKey = 'bond_id';

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'bond_id',
        'course_id',
    ];

    // ==================== Relationships ====================

    /**
     * @return BelongsTo<Bond, BondCourse>
     */
    public function bond(): BelongsTo
    {
        return $this->belongsTo(Bond::class, 'bond_id', 'id');
    }

    /**
     * @return BelongsTo<Course, BondCourse>
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id', 'id');
    }
}
