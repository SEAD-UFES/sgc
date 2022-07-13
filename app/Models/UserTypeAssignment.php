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
    use UserTypeAssignmentFilter, Filterable;

    /**
     *
     * @var array<string>
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
     *
     * @var array<string>
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
     * @var array<string>
     */
    protected $fillable = [
        'user_id',
        'user_type_id',
        'course_id',
        'begin',
        'end',
    ];

    /**
     *
     * @var array<string>
     */
    protected $observables = [
        'listed',
        'fetched',
    ];

    /**
     *
     * @var array<string>
     */
    private static $whiteListFilter = ['*'];

    /**
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo
     */
    public function userType()
    {
        return $this->belongsTo(UserType::class);
    }

    /**
     * @return BelongsTo
     */
    public function course()
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
