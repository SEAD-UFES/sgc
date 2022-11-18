<?php

namespace App\Models;

use App\Enums\Genders;
use App\Models\Filters\UserFilter;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Kyslik\ColumnSortable\Sortable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\CausesActivity;
use Spatie\Activitylog\Traits\LogsActivity;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use Sortable;
    use UserFilter;
    use Filterable;
    use CausesActivity;
    use LogsActivity;

    /**
     * @var bool
     */
    public $incrementing = true;

    /**
     * @var array<int, string>
     */
    public static $sortable = [
        'id',
        'login',
        'active',
        'created_at',
        'updated_at',
    ];

    /**
     * @var array<int, string>
     */
    public static $acceptedFilters = [
        'loginContains',
        // 'usertypeNameContains',
        'activeExactly',
        'employeeNameContains',
        'employeeId',
    ];

    /**
     * @var string
     */
    protected $table = 'users';

    /**
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'login',
        'password',
        'active',
        'employee_id',
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

    /**
     * @var array<int, string>
     *
     * @phpstan-ignore-next-line
     */
    private static $whiteListFilter = ['*'];

    // ==================== Relationships ====================

    /**
     * @return BelongsTo<Employee, User>
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }

    /**
     * @return HasMany<Responsibility>
     */
    public function responsibilities(): HasMany
    {
        return $this->hasMany(Responsibility::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*'])
            ->logExcept(['updated_at'])
            ->dontLogIfAttributesChangedOnly(['updated_at'])
            ->logOnlyDirty();
    }

    /**
     * Undocumented function
     *
     * @return Attribute<Genders, string>
     */
    protected function gender(): Attribute
    {
        return new Attribute(
            get: fn () => $this->employee?->gender ?? Genders::M,
            set: fn ($value) => $value === Genders::F ? 'F' : 'M',
        );
    }
}
