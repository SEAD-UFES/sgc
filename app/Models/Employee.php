<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Bond;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'cpf',
        'job',
        //gender
        'birthday',
        //'birth_state',
        'birth_city',
        'id_number',
        //document_type
        'id_issue_date',
        'id_issue_agency',
        //marital_status
        'spouse_name',
        'father_name',
        'mother_name',
        'address_street',
        'address_complement',
        'address_number',
        'address_district',
        'address_postal_code',
        //address_state
        'address_city',
        'area_code',
        'phone',
        'mobile',
        'email',
    ];

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

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'bonds')->withPivot('id', 'course_id', 'employee_id', 'role_id', 'pole_id', /* 'classroom_id',*/ 'begin', 'end', 'terminated_on', 'volunteer', 'impediment', 'impediment_description', 'uaba_checked_on',)->using(Bond::class)->as('bond')->withTimestamps();
    }

    public function employeeDocuments()
    {
        return $this->hasMany(EmployeeDocument::class);
    }

    public function hasDocuments()
    {
        return !is_null($this->employeeDocuments->first());
    }

    public function hasBond()
    {
        return ($this->courses->count() > 0);
    }

    public function isCoordinator()
    {
        $isCoord = false;

        if ($this->hasBond())
            foreach ($this->courses as $bonded_course)
                $isCoord = $isCoord || (substr($bonded_course->bond->role->name, 0, 5) == 'Coord');

        return $isCoord;
    }
}
