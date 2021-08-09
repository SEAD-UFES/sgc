<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Kyslik\ColumnSortable\Sortable;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use App\ModelFilters\userFilter;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    use Sortable;
    use userFilter, Filterable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        //'name',
        'email',
        'password',
        'active',
    ];

    public $sortable = ['id', 'email', 'active', 'created_at', 'updated_at'];

    private static $whiteListFilter = ['*'];
    public static $accepted_filters = [
        'email_contains',
        'usertype_name_contains',
        'active_exactly',
        'employee_name_contains',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
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
}
