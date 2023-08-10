<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BondCourseClass extends Model
{
    /**
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var string
     */
    protected $table = 'bond_course_class';

    /**
     * @var string
     */
    protected $primaryKey = 'bond_id';

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'bond_id',
        'course_class_id',
    ];

    // ==================== Relationships ====================

    /**
     * @return BelongsTo<Bond, BondCourseClass>
     */
    public function bond(): BelongsTo
    {
        return $this->belongsTo(Bond::class, 'bond_id', 'id');
    }

    /**
     * @return BelongsTo<CourseClass, BondCourseClass>
     */
    public function courseClass(): BelongsTo
    {
        return $this->belongsTo(CourseClass::class, 'course_class_id', 'id');
    }
}
