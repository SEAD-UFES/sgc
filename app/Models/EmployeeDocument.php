<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class EmployeeDocument extends Model
{
    use HasFactory;
    use Sortable;

    protected $fillable = [
        'original_name',
        'file_data',
    ];

    public $sortable = ['id', 'created_at', 'updated_at'];

    public function documentType()
    {
        return $this->belongsTo(DocumentType::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
