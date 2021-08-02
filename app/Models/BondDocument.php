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

    public function documentType()
    {
        return $this->belongsTo(DocumentType::class);
    }

    public function bond()
    {
        return $this->belongsTo(Bond::class, 'bond_id');
    }
}
