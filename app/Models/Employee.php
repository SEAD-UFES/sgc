<?php

namespace App\Models;

use App\ModelFilters\EmployeeFilter;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Kyslik\ColumnSortable\Sortable;

class Employee extends Model
{
    use HasFactory;
    use Sortable;
    use EmployeeFilter;
    use Filterable;

    /**
     * @var array<int, string>
     */
    public $sortable = [
        'id',
        'cpf',
        'name',
        'job',
        'address_city',
        'user.email',
        'created_at',
        'updated_at',
    ];

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

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'cpf',
        'job',
        'gender_id',
        'birthday',
        'birth_state_id',
        'birth_city',
        'id_number',
        'document_type_id',
        'id_issue_date',
        'id_issue_agency',
        'marital_status_id',
        'spouse_name',
        'father_name',
        'mother_name',
        'address_street',
        'address_complement',
        'address_number',
        'address_district',
        'address_postal_code',
        'address_state_id',
        'address_city',
        'area_code',
        'phone',
        'mobile',
        'email',
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
     * @return BelongsTo<Gender, Employee>
     */
    public function gender(): BelongsTo
    {
        return $this->belongsTo(Gender::class);
    }

    /**
     * @return BelongsTo<State, Employee>
     */
    public function birthState(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }

    /**
     * @return BelongsTo<DocumentType, Employee>
     */
    public function documentType(): BelongsTo
    {
        return $this->belongsTo(DocumentType::class);
    }

    /**
     * @return BelongsTo<MaritalStatus, Employee>
     */
    public function maritalStatus(): BelongsTo
    {
        return $this->belongsTo(MaritalStatus::class);
    }

    /**
     * @return BelongsTo<State, Employee>
     */
    public function addressState(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }

    /**
     * @return HasMany<User>
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * @return HasOne<User>
     */
    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }

    /**
     * @return BelongsToMany<Course>
     */
    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'bonds')->withPivot('id', 'course_id', 'employee_id', 'role_id', 'pole_id', /* 'classroom_id',*/ 'begin', 'end', 'terminated_at', 'volunteer', 'impediment', 'impediment_description', 'uaba_checked_at')->using(Bond::class)->as('bond')->withTimestamps();
    }

    /**
     * @return HasMany<EmployeeDocument>
     */
    public function employeeDocuments(): HasMany
    {
        return $this->hasMany(EmployeeDocument::class);
    }

    /**
     * @return HasMany<Bond>
     */
    public function bonds(): HasMany
    {
        return $this->hasMany(Bond::class);
    }

    /**
     * @return bool
     */
    public function hasDocuments(): bool
    {
        return ! is_null($this->employeeDocuments->first());
    }

    /**
     * @return bool
     */
    public function hasBond(): bool
    {
        return $this->courses->count() > 0;
    }

    /**
     * @return bool
     */
    public function isCourseCoordinator(): bool
    {
        $isCoord = false;

        if ($this->hasBond()) {
            foreach ($this->courses as $bonded_course) {
                $isCoord = $isCoord || (substr($bonded_course->bond->role->name, 0, 20) === 'Coordenador de curso');
            }
        }
        return $isCoord;
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
