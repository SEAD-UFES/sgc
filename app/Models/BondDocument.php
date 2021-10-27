<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class BondDocument extends Model
{
    use HasFactory;
    use Sortable;

    protected $table = 'bond_documents';

    const REFERENT_ID = 'bond_id';

    protected $fillable = [
        'bond_id',
    ];

    protected $observables = [
        'listed',
        'viewed',
    ];

    public $sortable = [
        'id',
    ];

    public static $accepted_filters = [
        'originalname_contains',
        'documentType_name_contains',
        'bond',
        'bond_employee_name_contains',
        'bond_role_name_contains',
        'bond_pole_name_contains',
        'bond_course_name_contains'
    ];

    public function document()
    {
        return $this->morphOne(Document::class, 'documentable');
    }

    public function bond()
    {
        return $this->belongsTo(Bond::class, 'bond_id');
    }

    //metodo de ordenação para (bond->employee->name) no sortable
    public function bondEmployeeNameSortable($query, $direction)
    {
        $query = $query
            ->join('bonds', 'bond_documents.bond_id', '=', 'bonds.id')
            ->join('employees', 'bonds.employee_id', '=', 'employees.id')
            ->orderBy('employees.name', $direction)
            ->select('bond_documents.*');
        return $query;
    }

    //metodo de ordenação para (bond->role->name) no sortable
    public function bondRoleNameSortable($query, $direction)
    {
        $query = $query
            ->join('bonds', 'bond_documents.bond_id', '=', 'bonds.id')
            ->join('roles', 'bonds.role_id', '=', 'roles.id')
            ->orderBy('roles.name', $direction)
            ->select('bond_documents.*');
        return $query;
    }

    //metodo de ordenação para (bond->course->name) no sortable
    public function bondCourseNameSortable($query, $direction)
    {
        $query = $query
            ->join('bonds', 'bond_documents.bond_id', '=', 'bonds.id')
            ->join('courses', 'bonds.course_id', '=', 'courses.id')
            ->orderBy('courses.name', $direction)
            ->select('bond_documents.*');
        return $query;
    }

    //metodo de ordenação para (bond->pole->name) no sortable
    public function bondPoleNameSortable($query, $direction)
    {
        $query = $query
            ->join('bonds', 'bond_documents.bond_id', '=', 'bonds.id')
            ->join('poles', 'bonds.course_id', '=', 'poles.id')
            ->orderBy('poles.name', $direction)
            ->select('bond_documents.*');
        return $query;
    }

    public function logListed()
    {
        $this->fireModelEvent('listed', false);
    }

    public function logViewed()
    {
        $this->fireModelEvent('viewed', false);
    }
}
