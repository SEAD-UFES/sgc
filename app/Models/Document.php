<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use App\ModelFilters\documentFilter;

class Document extends Model
{
    use HasFactory;
    use Sortable;
    use documentFilter, Filterable;

    protected $table = 'documents';

    protected $fillable = [
        'original_name',
        'document_type_id',
        'documentable_type',
        'documentable_id',
        'file_data',
    ];

    protected $observables = [
        'listed',
        'retrieved',
    ];

    public $sortable = [
        'id',
        'original_name',
        'created_at',
        'updated_at'
    ];

    private static $whiteListFilter = ['*'];

    public function employeeModel()
    {
        return $this->documentable()->first()->employee()->first();
    }

    public function employeeDocument()
    {
        return $this->belongsTo(EmployeeDocument::class, 'documentable_id');
    }

    public function documentType()
    {
        return $this->belongsTo(DocumentType::class);
    }

    public function documentable()
    {
        return $this->morphTo();
    }

    public static function employeeDocumentsByEmployeeId($employeeId)
    {
        return Document::whereHasMorph('documentable', 'App\Models\EmployeeDocument', function ($query) use ($employeeId) {
            $query->where('employee_documents.employee_id', $employeeId);
        });
    }

    public static function bondDocumentsByBondId($bondId)
    {
        return Document::whereHasMorph('documentable', 'App\Models\BondDocument', function ($query) use ($bondId) {
            $query->where('bond_documents.bond_id', $bondId);
        });
    }

    public static function rightsDocumentsByBondId($bondId)
    {
        $documentType = DocumentType::where('name', 'Ficha de Inscrição - Termos e Licença')->first();

        return Document::where('documents.document_type_id', $documentType->id)
            ->whereHasMorph('documentable', 'App\Models\BondDocument', function ($query) use ($bondId) {
                $query->where('bond_documents.bond_id', $bondId);
            });
    }

    public static function rightsWithBond()
    {
        $documentType = DocumentType::where('name', 'Ficha de Inscrição - Termos e Licença')->first();

        return Document::where('documents.document_type_id', $documentType->id)
            ->whereHasMorph('documentable', 'App\Models\BondDocument', function ($query) {
                $query->whereHas('bond', function ($bondQuery) {
                    $bondQuery->whereNotNull('uaba_checked_at')->where('impediment', false);
                });
            })->with('documentable.bond');
    }

    public function logListed()
    {
        $this->fireModelEvent('listed', false);
    }

    public function logViewed()
    {
        $this->fireModelEvent('retrieved', false);
    }

    //metodo de ordenação para (bond->employee->name) no sortable
    /* public function bondEmployeeNameSortable($query, $direction)
    {
        $query = $query
            ->join('bonds', 'bond_documents.bond_id', '=', 'bonds.id')
            ->join('employees', 'bonds.employee_id', '=', 'employees.id')
            ->orderBy('employees.name', $direction)
            ->select('bond_documents.*');
        return $query;
    } */

    //metodo de ordenação para (bond->role->name) no sortable
    /* public function bondRoleNameSortable($query, $direction)
    {
        $query = $query
            ->join('bonds', 'bond_documents.bond_id', '=', 'bonds.id')
            ->join('roles', 'bonds.role_id', '=', 'roles.id')
            ->orderBy('roles.name', $direction)
            ->select('bond_documents.*');
        return $query;
    } */

    //metodo de ordenação para (bond->course->name) no sortable
    /* public function bondCourseNameSortable($query, $direction)
    {
        $query = $query
            ->join('bonds', 'bond_documents.bond_id', '=', 'bonds.id')
            ->join('courses', 'bonds.course_id', '=', 'courses.id')
            ->orderBy('courses.name', $direction)
            ->select('bond_documents.*');
        return $query;
    } */

    //metodo de ordenação para (bond->pole->name) no sortable
    /* public function bondPoleNameSortable($query, $direction)
    {
        $query = $query
            ->join('bonds', 'bond_documents.bond_id', '=', 'bonds.id')
            ->join('poles', 'bonds.course_id', '=', 'poles.id')
            ->orderBy('poles.name', $direction)
            ->select('bond_documents.*');
        return $query;
    } */
}
