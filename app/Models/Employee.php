<?php

namespace App\Models;

use App\ModelFilters\EmployeeFilter;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Employee extends Model
{
    use HasFactory;
    use Sortable;
    use EmployeeFilter, Filterable;

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
    public static $accepted_filters = [
        'cpfContains',
        'nameContains',
        'jobContains',
        'addresscityContains',
        'userEmailContains',
    ];

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

    protected $observables = [
        'listed',
        'fetched',
    ];

    private static $whiteListFilter = ['*'];

    public function gender()
    {
        return $this->belongsTo(Gender::class);
    }

    public function birthState()
    {
        return $this->belongsTo(State::class);
    }

    public function documentType()
    {
        return $this->belongsTo(DocumentType::class);
    }

    public function maritalStatus()
    {
        return $this->belongsTo(MaritalStatus::class);
    }

    public function addressState()
    {
        return $this->belongsTo(State::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'bonds')->withPivot('id', 'course_id', 'employee_id', 'role_id', 'pole_id', /* 'classroom_id',*/ 'begin', 'end', 'terminated_at', 'volunteer', 'impediment', 'impediment_description', 'uaba_checked_at')->using(Bond::class)->as('bond')->withTimestamps();
    }

    public function employeeDocuments()
    {
        return $this->hasMany(EmployeeDocument::class);
    }

    public function bonds()
    {
        return $this->hasMany(Bond::class);
    }

    public function hasDocuments()
    {
        return ! is_null($this->employeeDocuments->first());
    }

    public function hasBond()
    {
        return $this->courses->count() > 0;
    }

    public function isCourseCoordinator()
    {
        $isCoord = false;

        if ($this->hasBond()) {
            foreach ($this->courses as $bonded_course) {
                $isCoord = $isCoord || (substr($bonded_course->bond->role->name, 0, 20) === 'Coordenador de curso');
            }
        }
        return $isCoord;
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
