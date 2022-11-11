<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class CourseClass extends Model
{
    use HasFactory;
    use LogsActivity;

    /**
     * @var string
     */
    protected $table = 'course_classes';

    /**
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * @var bool
     */
    public $incrementing = true;

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'course_id',
        'code',
        'name',
        'cpp',
    ];

    /**
     * @return HasMany<Bond>
     */
    public function bonds(): HasMany
    {
        return $this->hasMany(Bond::class, 'course_class_id');
    }

    /**
     * @return BelogsTo<Course>
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id');
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
