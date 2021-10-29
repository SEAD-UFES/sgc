<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentType extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'description',
    ];

    protected $observables = [
        'listed',
        'retrieved',
    ];
 
    public function employeeDocuments()
    {
        return $this->hasMany(EmployeeDocument::class);
    }
 
    public function bondDocuments()
    {
        return $this->hasMany(BondDocument::class);
    }

    public function logListed()
    {
        $this->fireModelEvent('listed', false);
    }

    public function logViewed()
    {
        $this->fireModelEvent('retrieved', false);
    }
}
