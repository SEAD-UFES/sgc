<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ApprovedState extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
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
     * @return HasMany<Approved>
     */
    public function approveds(): HasMany
    {
        return $this->hasMany(Approved::class);
    }

    /**
     * @return bool
     */
    public function hasNext(): bool
    {
        return !($this->name === 'Desistente' or $this->name === 'Aceitante');
    }

    /**
     * @return ApprovedState|null
     */
    public function getNext(): ApprovedState|null
    {
        if ($this->hasNext()) {
            switch ($this->name) {
                case 'Não contatado':
                    return ApprovedState::where('name', 'Contatado')->first();
                case 'Contatado':
                    return ApprovedState::where('name', 'Aceitante')->orWhere('name', 'Desistente')->first();
            }
        }

        return null;
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
