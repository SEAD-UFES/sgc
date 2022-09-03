<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ApprovedState extends Model
{
    use HasFactory;
    use LogsActivity;

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
        return $this->name !== 'Desistente' && $this->name !== 'Aceitante';
    }

    /**
     * @return ApprovedState|null
     */
    public function getNext(): ApprovedState|null
    {
        if ($this->hasNext()) {
            if ($this->name === 'Não contatado') {
                return ApprovedState::where('name', 'Contatado')->first();
            }
            if ($this->name === 'Contatado') {
                return ApprovedState::where('name', 'Aceitante')->orWhere('name', 'Desistente')->first();
            }
        }

        return null;
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*'])
            ->logExcept(['updated_at'])
            ->dontLogIfAttributesChangedOnly(['updated_at'])
            ->logOnlyDirty();
    }
}
