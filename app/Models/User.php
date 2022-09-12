<?php

namespace App\Models;

use App\Enums\Genders;
use App\ModelFilters\UserFilter;
use Carbon\Carbon;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;
use Kyslik\ColumnSortable\Sortable;
// use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\CausesActivity;
use Spatie\Activitylog\Traits\LogsActivity;

class User extends Authenticatable
{
    //use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use Sortable;
    use UserFilter;
    use Filterable;
    use CausesActivity;
    use LogsActivity;

    /**
     * @var array<int, string>
     */
    public $sortable = ['id', 'email', 'active', 'created_at', 'updated_at'];

    /**
     * @var array<int, string>
     */
    public static $accepted_filters = [
        'emailContains',
        // 'usertypeNameContains',
        'activeExactly',
        'employeeNameContains',
        'employeeId',
    ];

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

    /**
     * @return BelongsTo<Employee, User>
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * @return HasMany<Responsibility>
     */
    public function userTypeAssignments(): HasMany
    {
        return $this->responsibilities();
    }

    /**
     * @return HasMany<Responsibility>
     */
    public function responsibilities(): HasMany
    {
        return $this->hasMany(Responsibility::class);
    }

    /**
     * @param Builder<User> $query
     *
     * @return Builder<User>
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('active', true);
    }

    /**
     * @return HasMany<Responsibility>
     */
    public function getResponsibilities(): HasMany
    {
        return $this->responsibilities()
            ->with('userType', 'course')
            ->join('user_types', 'responsibilities.user_type_id', '=', 'user_types.id')
            ->select('responsibilities.*')
            ->orderBy('user_types.name', 'asc');
    }

    /**
     * @return HasMany<Responsibility>
     */
    public function getActiveResponsibilities(): HasMany
    {
        return $this->getResponsibilities()->active();
    }

    /**
     * @param Builder<User> $query
     * @param int $userTypeId
     *
     * @return  Builder<User>
     */
    public function scopeOfActiveType(Builder $query, int $userTypeId): Builder
    {
        return $query
            ->join('responsibilities AS responsibilities_A', 'users.id', '=', 'responsibilities_A.user_id')
            ->addSelect('users.*')
            ->where('responsibilities_A.user_type_id', $userTypeId)
            ->where('responsibilities_A.begin', '<=', Carbon::today()->toDateString())
            ->where(static function ($q) {
                $q->where('responsibilities_A.end', '>=', Carbon::today()->toDateString())
                    ->orWhereNull('responsibilities_A.end');
            });
    }

    /**
     * @param Builder<User> $query
     * @param int $courseId
     *
     * @return Builder<User>
     */
    public function scopeOfCourse($query, $courseId): Builder
    {
        return $query
            ->join('responsibilities AS responsibilities_B', 'users.id', '=', 'responsibilities_B.user_id')
            ->select('users.*')
            ->where('responsibilities_B.course_id', $courseId);
    }

    //permission system

    /**
     * @return bool
     */
    public function hasActiveResponsibilities(): bool
    {
        return $this->getActiveResponsibilities()->count() > 0;
    }

    /**
     * @return Responsibility|null
     */
    public function getFirstActiveResponsibility(): ?Responsibility
    {
        return $this->getActiveResponsibilities()->limit(1)->first();
    }

    /**
     * @param ?int $responsibilityId
     *
     * @return void
     */
    public function setCurrentResponsibility(?int $responsibilityId): void
    {
        $responsibility = $this
            ->getActiveResponsibilities()
            ->whereKey($responsibilityId)
            ->first();

        session(['loggedInUser.currentResponsibility' => $responsibility]);
    }

    /**
     * @return Responsibility|null
     */
    public function getCurrentResponsibility(): ?Responsibility
    {
        /**
         * @var int $currentUserId
         */
        $currentUserId = $this->id;

        /**
         * @var Collection<int, Responsibility> $storedResponsibilities
         */
        $storedResponsibilities = Cache::remember(
            'responsibilities.' . $currentUserId,
            30,
            function () {
                return $this->getActiveResponsibilities()->get();
            }
        );

        /**
         * @var ?Responsibility $sessionResponsibility
         */
        $sessionResponsibility = session('loggedInUser.currentResponsibility');

        /**
         * @var Responsibility $responsibility
         */
        $responsibility = $storedResponsibilities->where('id', $sessionResponsibility?->id)->first();

        return $responsibility;
    }

    /**
     * @return void
     */
    public function unlinkEmployee(): void
    {
        $this->employee()->dissociate();
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
     * Get the user's name.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute<string, string>
     */
    protected function name(): Attribute
    {
        return new Attribute(
            get: fn ($value) => $this->employee ? $this->employee->name : $this->email,
        );
    }

    /**
     * Get the user's gender article.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute<string, string>
     */
    protected function genderArticle(): Attribute
    {
        /**
         * @var ?Employee $employee
         */
        $employee = $this->employee;

        if (is_null($employee)) {
            return new Attribute(
                get: fn ($value) => 'o(a)',
            );
        }


        /**
         * @var ?Genders $gender
         */
        $gender = $this->employee?->gender;

        if (is_null($gender)) {
            return new Attribute(
                get: fn ($value) => 'o(a)',
            );
        }

        return new Attribute(
            get: fn ($value) => $this->employee?->gender?->value === 'Masculino' ? 'o' : 'a',
        );
    }
}
