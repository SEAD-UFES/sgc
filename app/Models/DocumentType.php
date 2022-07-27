<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DocumentType extends Model
{
    use HasFactory;

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * @var array<int, string>
     */
    protected $observables = [
        'listed',
        'fetched',
    ];

    /**
     * @return HasMany<EmployeeDocument>
     */
    public function employeeDocuments(): HasMany
    {
        return $this->hasMany(EmployeeDocument::class);
    }

    /**
     * @return HasMany<BondDocument>
     */
    public function bondDocuments(): HasMany
    {
        return $this->hasMany(BondDocument::class);
    }

    /**
     * @return void
     */
    public function logListed(): void
    {
        $this->fireModelEvent('listed', false);
    }

    /**
     * @return void
     */
    public function logFetched(): void
    {
        $this->fireModelEvent('fetched', false);
    }
}
