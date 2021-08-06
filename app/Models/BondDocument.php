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

    //sobrepor metodo de ordenação para vinculo (bond->emplyee->name) no sortable
    public function vinculoSortable($query, $direction)
    {
        $query = $query
            ->join('bonds', 'bond_documents.bond_id', '=', 'bonds.id')
            ->join('employees', 'bonds.employee_id', '=', 'employees.id')
            ->orderBy('employees.name', $direction)
            ->select('bond_documents.*');
        return $query;
    }
}
