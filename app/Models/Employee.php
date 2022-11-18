<?php

namespace App\Models;

use App\Enums\Genders;
use App\Models\Filters\EmployeeFilter;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
     * @var bool
     */
    public $incrementing = true;

    /**
     * @var array<int, string>
     */
    public static $sortable = [
        'id',
        'cpf',
        'name',
        'created_at',
        'updated_at',
    ];

    /**
     * @var array<int, string>
     */
    public static $acceptedFilters = [
        'cpfContains',
        'nameContains',
        'jobContains',
        'addresscityContains',
        'userEmailContains',
    ];

    /**
     * @var string
     */
    protected $table = 'employees';

    /**
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'cpf',
        'name',
        'gender',
        'email',
    ];

    // ==================== Casts ====================

    protected $casts = [
        'gender' => Genders::class,
    ];

    /**
     * @var array<int, string>
     *
     * @phpstan-ignore-next-line
     */
    private static $whiteListFilter = ['*'];

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

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*'])
            ->logExcept(['updated_at'])
            ->dontLogIfAttributesChangedOnly(['updated_at'])
            ->logOnlyDirty();
    }
}
