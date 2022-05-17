<?php

namespace App\Models;

use App\ModelFilters\UserTypeAssignmentFilter;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class UserTypeAssignment extends Model
{
    use HasFactory;
    use Sortable;
    use UserTypeAssignmentFilter, Filterable;

    public $sortable = [
        'id',
        'user.email',
        'userType.name',
        'begin',
        'end',
        'created_at',
        'updated_at',
    ];
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
     * @var array
     */
    protected $fillable = [
        'user_id',
        'user_type_id',
        'course_id',
        'begin',
        'end',
    ];

    protected $observables = [
        'listed',
        'fetched',
    ];

    private static $whiteListFilter = ['*'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function userType()
    {
        return $this->belongsTo(UserType::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function logListed()
    {
        $this->fireModelEvent('listed', false);
    }

    public function logFetched()
    {
        $this->fireModelEvent('fetched', false);
    }
}
