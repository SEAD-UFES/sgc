<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use App\ModelFilters\bondDocumentFilter;

class BondDocument extends Model
{
    use HasFactory;
    use Sortable;
    use bondDocumentFilter, Filterable;

    protected $table = 'bond_documents';

    protected $fillable = [
        'original_name',
        'document_type_id',
        'bond_id',
        'file_data',
    ];

    public $sortable = [
        'id',
        'original_name',
        'created_at',
        'updated_at'
    ];

    private static $whiteListFilter = ['*'];
    public static $accepted_filters = [
        'bond',
        'originalname_contains',
        'documentType_name_contains',
        'bond_employee_name_contains',
        'bond_role_name_contains',
        'bond_course_name_contains'
    ];

    public function documentType()
    {
        return $this->belongsTo(DocumentType::class);
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
}
