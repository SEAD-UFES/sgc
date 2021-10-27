<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class EmployeeDocument extends Model
{
    use HasFactory;
    use Sortable;

    protected $table = 'employee_documents';

    const REFERENT_ID = 'employee_id';

    protected $fillable = [
        'employee_id',
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
        'employee_name_contains',
        'employee_cpf_contains',
    ];

    public function document()
    {
        return $this->morphOne(Document::class, 'documentable');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
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
