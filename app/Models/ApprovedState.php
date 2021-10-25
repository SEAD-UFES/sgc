<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApprovedState extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
    ];

    protected $observables = [
        'listed',
        'viewed',
    ];

    public function approveds()
    {
        return $this->hasMany(Approved::class);
    }

    public function hasNext()
    {
        if ($this->name == 'Desistente' or $this->name == 'Aceitante')
            return false;

        return true;
    }

    public function getNext()
    {
        if ($this->hasNext())
            switch ($this->name) {
                case 'Não contatado':
                    return ApprovedState::where('name', 'Contatado')->get();
                    break;
                case 'Contatado':
                    return ApprovedState::where('name', 'Aceitante')->orWhere('name', 'Desistente')->get();
                    break;
            }
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
