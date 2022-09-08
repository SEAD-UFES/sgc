<?php

namespace App\Models;

use App\ModelFilters\ResponsibilityFilter;
use Carbon\Carbon;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Kyslik\ColumnSortable\Sortable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Responsibility extends Model
{
    use HasFactory;
    use Sortable;
    use ResponsibilityFilter;
    use Filterable;
    use LogsActivity;

    /**
     * @var array<int, string>
     */
    public $sortable = [
        'id',
        'user.email',
        'userType.name',
        'begin',
        'end',
        'created_at',
        'updated_at',
    ];

    /**
     * @var array<int, string>
     */
    public static $accepted_filters = [
        'userEmailContains',
        'usertypeNameContains',
        'courseNameContains',
        'beginExactly',
        'beginBigOrEqu',
        'beginLowOrEqu',
        'endExactly',
        'endBigOrEqu',
        'endLowOrEqu',
        'userId',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'user_type_id',
        'course_id',
        'begin',
        'end',
    ];

    /**
     * @var array<int, string>
     */
    private static $whiteListFilter = ['*'];

    /**
     * @return BelongsTo<User, Responsibility>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo<UserType, Responsibility>
     */
    public function userType(): BelongsTo
    {
        return $this->belongsTo(UserType::class);
    }

    /**
     * @return BelongsTo<Course, Responsibility>
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * @param Builder<Responsibility> $query
     *
     * @return Builder<Responsibility>
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('begin', '<=', Carbon::today()->toDateString())
            ->where(static function ($query) {
                $query->where('end', '>=', Carbon::today()->toDateString())
                    ->orWhereNull('end');
            });
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
