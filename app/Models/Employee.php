<?php

namespace App\Models;

use App\Enums\Genders;
use App\Enums\MaritalStatuses;
use App\ModelFilters\EmployeeFilter;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Kyslik\ColumnSortable\Sortable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Employee extends Model
{
    use HasFactory;
    use Sortable;
    use EmployeeFilter;
    use Filterable;
    use LogsActivity;

    /**
     * @var string
     */
    protected $table = 'employees';

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
        'cpf',
        'name',
        'gender',
        'email',
    ];

    // /**
    //  * @var array<int, string>
    //  */
    // public $sortable = [
    //     'id',
    //     'cpf',
    //     'name',
    //     'job',
    //     'address_city',
    //     'user.email',
    //     'created_at',
    //     'updated_at',
    // ];

    /**
     * @var array<int, string>
     */
    public static $accepted_filters = [
        'cpfContains',
        'nameContains',
        'jobContains',
        'addresscityContains',
        'userEmailContains',
    ];

    // /**
    //  * @var array<int, string>
    //  *
    //  * @phpstan-ignore-next-line
    //  */
    // private static $whiteListFilter = ['*'];

    // ==================== Casts ====================

    protected $casts = [
        'gender' => Genders::class,
    ];

    // ==================== Accessors and Mutators ====================

    // public function getGenderAttribute()
    // {
    //     return Genders::fromName($this->getAttribute('gender'));
    // }

    // protected function gender(): Attribute
    // {
    //     return Attribute::make(
    //         get: fn () => Employee::find($this->id)->pluck('gender') === 'F' ? Genders::F : Genders::M,
    //         set: fn ($value) => $value === Genders::F ? 'F' : 'M',
    //     );
    // }


    // ==================== Relationships ====================

    /**
     * @return HasOne<Identity>
     */
    public function identity(): HasOne
    {
        return $this->hasOne(Identity::class, 'employee_id', 'id');
    }

    /**
     * @return HasOne<PersonalDetail>
     */
    public function personalDetail(): HasOne
    {
        return $this->hasOne(PersonalDetail::class);
    }

    /**
     * @return HasOne<Spouse>
     */
    public function spouse(): HasOne
    {
        return $this->hasOne(Spouse::class);
    }

    /**
     * @return HasOne<BankAccount>
     */
    public function bankAccount(): HasOne
    {
        return $this->hasOne(BankAccount::class);
    }

    /**
     * @return HasOne<InstitutionalDetail>
     */
    public function institutionalDetail(): HasOne
    {
        return $this->hasOne(InstitutionalDetail::class);
    }

    /**
     * @return HasOne<Address>
     */
    public function address(): HasOne
    {
        return $this->hasOne(Address::class);
    }

    /**
     * @return HasOne<User>
     */
    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }

    /**
     * @return HasMany<Phone>
     */
    public function phones(): HasMany
    {
        return $this->hasMany(Phone::class);
    }

    /**
     * @return HasMany<Bond>
     */
    public function bonds(): HasMany
    {
        return $this->hasMany(Bond::class);
    }

    /**
     * @return HasMany<Impediment>
     */
    public function createdImpediments(): HasMany
    {
        return $this->hasMany(Impediment::class, 'reviewer_id');
    }

    /**
     * @return HasMany<Impediment>
     */
    public function closedImpediments(): HasMany
    {
        return $this->hasMany(Impediment::class, 'closed_by_id');
    }

    // =========================

    // /**
    //  * @return BelongsToMany<Course>
    //  */
    // public function courses(): BelongsToMany
    // {
    //     return $this->belongsToMany(Course::class, 'bonds')->withPivot('id', 'course_id', 'employee_id', 'role_id', 'pole_id', /* 'classroom_id',*/ 'begin', 'end', 'terminated_at', 'volunteer', 'impediment', 'impediment_description', 'uaba_checked_at')->using(Bond::class)->as('bond')->withTimestamps();
    // }

    // /**
    //  * @return bool
    //  */
    // public function hasBond(): bool
    // {
    //     return $this->bonds->count() > 0;
    // }

    // /**
    //  * @param Builder<Employee> $query
    //  * @param int $courseId
    //  *
    //  * @return Builder<Employee>
    //  */
    // public function scopeByCourse(Builder $query, ?int $courseId = null): Builder
    // {
    //     if ($courseId === null) {
    //         return $query->join('bonds as bonds_A', 'bonds_A.employee_id', '=', 'employees.id')
    //             ->join('courses as courses_A', 'courses_A.id', '=', 'bonds_A.course_id')
    //             ->addSelect('courses_A.name as course_name');
    //     }

    //     return $query->join('bonds as bonds_A', 'bonds_A.employee_id', '=', 'employees.id')
    //         ->join('courses as courses_A', 'courses_A.id', '=', 'bonds_A.course_id')
    //         ->where('bonds_A.course_id', $courseId)
    //         ->addSelect('courses_A.name as course_name');
    // }

    // /**
    //  * @param Builder<Employee> $query
    //  *
    //  * @return Builder<Employee>
    //  */
    // public function scopeCoordinator(Builder $query): Builder
    // {
    //     return $query->where('employees.job', 'like', 'Coord%')
    //         ->addSelect('employees.job as job');
    // }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*'])
            ->logExcept(['updated_at'])
            ->dontLogIfAttributesChangedOnly(['updated_at'])
            ->logOnlyDirty();
    }
}
