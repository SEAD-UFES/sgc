<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use App\ModelFilters\employeeDocumentFilter;

class EmployeeDocument extends Model
{
    use HasFactory;
    use Sortable;
    use employeeDocumentFilter, Filterable;

    protected $fillable = [
        'original_name',
        'document_type_id',
        'employee_id',
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
        'employee_cpf_contains',
        'employee_name_contains',
        'originalname_contains',
        'documentType_name_contains'
    ];

    public function documentType()
    {
        return $this->belongsTo(DocumentType::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
