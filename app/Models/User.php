<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Kyslik\ColumnSortable\Sortable;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use App\ModelFilters\UserFilter;
use Carbon\Carbon;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    use Sortable;
    use UserFilter, Filterable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        //'name',
        'email',
        'password',
        'active',
    ];

    protected $observables = [
        'listed',
        'fetched',
    ];

    public $sortable = ['id', 'email', 'active', 'created_at', 'updated_at'];

    private static $whiteListFilter = ['*'];
    public static $accepted_filters = [
        'emailContains',
        // 'usertypeNameContains',
        'activeExactly',
        'employeeNameContains',
        'employeeId'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function userType()
    {
        return $this->belongsTo(UserType::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function userTypeAssignments($options = [])
    {
        return $this->hasMany(UserTypeAssignment::class);
    }

    //dynamic > static :)
    public function getActiveUTAs()
    {
        $result =  $this->userTypeAssignments()
            ->with('userType', 'course')
            ->join('user_types', 'user_type_assignments.user_type_id', '=', 'user_types.id')
            ->select('user_type_assignments.*')
            ->where(
                function ($query) {
                    $query
                        ->where([
                            ['begin', '<=', Carbon::today()->toDateString()],
                            ['end', '>=', Carbon::today()->toDateString()],
                        ])
                        ->orWhere([
                            ['begin', '<=', Carbon::today()->toDateString()],
                            ['end', '=', null],
                        ]);
                }
            )
            ->orderBy('user_types.name', 'asc');
        return $result;
    }

    public function scopeWhereActiveUserType($query, $id)
    {
        return $query
            ->join('user_type_assignments AS user_type_assignments_A', 'users.id', '=', 'user_type_assignments_A.user_id')
            ->where('user_type_assignments_A.user_type_id', $id)
            ->where(
                function ($query) {
                    $query
                        ->where([
                            ['user_type_assignments_A.begin', '<=', Carbon::today()->toDateString()],
                            ['user_type_assignments_A.end', '>=', Carbon::today()->toDateString()],
                        ])
                        ->orWhere([
                            ['user_type_assignments_A.begin', '<=', Carbon::today()->toDateString()],
                            ['user_type_assignments_A.end', '=', null],
                        ]);
                }
            )
            ->select('users.*');
    }

    public function scopeWhereUtaCourseId($query, $id)
    {
        return $query
            ->join('user_type_assignments AS user_type_assignments_B', 'users.id', '=', 'user_type_assignments_B.user_id')
            ->where('user_type_assignments_B.course_id', $id)
            ->select('users.*');
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
