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

    protected $table = 'employee_documents';

    const REFERENT_ID = 'employee_id';

    protected $fillable = [
        'employee_id',
    ];

    public $sortable = [
        'id',
    ];

    private static $whiteListFilter = ['*'];
    public static $accepted_filters = [
        'employee_cpf_contains',
        'employee_name_contains',
        /* 'originalname_contains',
        'documentType_name_contains' */
    ];

    public function document()
    {
        return $this->morphOne(Document::class, 'documentable');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
