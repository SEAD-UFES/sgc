<?php

namespace App\Models;

use App\ModelFilters\UserTypeAssignmentFilter;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Kyslik\ColumnSortable\Sortable;

class UserTypeAssignment extends Model
{
    use HasFactory;
    use Sortable;
    use UserTypeAssignmentFilter;
    use Filterable;

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
    protected $observables = [
        'listed',
        'fetched',
    ];

    /**
     * @var array<int, string>
     */
    private static $whiteListFilter = ['*'];

    /**
     * @return BelongsTo<User, UserTypeAssignment>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo<UserType, UserTypeAssignment>
     */
    public function userType(): BelongsTo
    {
        return $this->belongsTo(UserType::class);
    }

    /**
     * @return BelongsTo<Course, UserTypeAssignment>
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * @return void
     */
    public function logListed(): void
    {
        $this->fireModelEvent('listed', false);
    }

    /**
     * @return void
     */
    public function logFetched(): void
    {
        $this->fireModelEvent('fetched', false);
    }
}
