<?php

namespace App\Models;

use App\ModelFilters\UserFilter;
use Carbon\Carbon;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use InvalidArgumentException;
use Kyslik\ColumnSortable\Sortable;
// use Laravel\Sanctum\HasApiTokens;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class User extends Authenticatable
{
    //use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use Sortable;
    use UserFilter;
    use Filterable;

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
    ];

    /**
     * @var array<int, string>
     */
    protected $observables = [
        'listed',
        'fetched',
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
     */
    private static $whiteListFilter = ['*'];

    /**
     * @return BelongsTo<UserType, User>
     */
    public function userType(): BelongsTo
    {
        return $this->belongsTo(UserType::class);
    }

    /**
     * @return BelongsTo<Employee, User>
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * @return HasMany<UserTypeAssignment>
     */
    public function userTypeAssignments(): HasMany
    {
        return $this->hasMany(UserTypeAssignment::class);
    }

    /**
     * @return HasMany<UserTypeAssignment>
     */
    //dynamic > static :)
    public function getActiveUtas(): HasMany
    {
        return $this->userTypeAssignments()
            ->with('userType', 'course')
            ->join('user_types', 'user_type_assignments.user_type_id', '=', 'user_types.id')
            ->select('user_type_assignments.*')
            ->where(
                static function ($query) {
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
    }

    /**
     * @param Builder<User> $query
     * @param string $id
     *
     * @return Builder<User>
     */
    public function scopeWhereActiveUserType($query, $id): Builder
    {
        return $query
            ->join('user_type_assignments AS user_type_assignments_A', 'users.id', '=', 'user_type_assignments_A.user_id')
            ->where('user_type_assignments_A.user_type_id', $id)
            ->where(
                static function ($query) {
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

    /**
     * @param Builder<User> $query
     * @param string $id
     *
     * @return Builder<User>
     */
    public function scopeWhereUtaCourseId($query, $id): Builder
    {
        return $query
            ->join('user_type_assignments AS user_type_assignments_B', 'users.id', '=', 'user_type_assignments_B.user_id')
            ->where('user_type_assignments_B.course_id', $id)
            ->select('users.*');
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

    //permission system

    /**
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    public function hasUtas(): bool
    {
        $activeUtas = $this->getActiveUtas()->get();
        return $activeUtas->count() > 0;
    }

    /**
     * @return UserTypeAssignment|null
     *
     * @throws InvalidArgumentException
     */
    public function getFirstUta(): ?UserTypeAssignment
    {
        return $this->getActiveUtas()->first();
    }

    /**
     * @param int|null $user_type_assignment_id
     *
     * @return void
     *
     * @throws InvalidArgumentException
     * @throws ModelNotFoundException
     * @throws BindingResolutionException
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    public function setCurrentUta(?int $user_type_assignment_id): void
    {
        if ($user_type_assignment_id) {
            $user_type_assignment = $this
                ->getActiveUtas()
                ->where('user_type_assignments.id', $user_type_assignment_id)
                ->firstOrFail();

            session(['loggedInUser.currentUta' => $user_type_assignment]);
        //session(['loggedInUser.currentUtaId' => $user_type_assignment?->id]);
        } else {
            session(['loggedInUser.currentUta' => null]);
            //session(['loggedInUser.currentUtaId' => null]);
        }
    }

    /**
     * @return UserTypeAssignment|null
     *
     * @throws BindingResolutionException
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    public function getCurrentUta(): ?UserTypeAssignment
    {
        /* $user_type_assignment = $this
            ->getActiveUtas()
            ->where('user_type_assignments.id', session('loggedInUser.currentUtaId'))
            ->first();

        return $user_type_assignment; */

        /** @var UserTypeAssignment $user_type_assignment */
        return session('loggedInUser.currentUta');
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
        return new Attribute(
            get: fn ($value) => $this->employee ? ($this->employee->gender ? ($this->employee->gender->name === 'Masculino' ? 'o' : 'a') : 'o(a)') : 'o(a)',
        );
    }
}
