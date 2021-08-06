<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;

class EmployeeDocument extends Model
{
    use HasFactory;
    use Sortable;
    use Filterable;

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
    public static $accepted_filters = [];

    public function documentType()
    {
        return $this->belongsTo(DocumentType::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
