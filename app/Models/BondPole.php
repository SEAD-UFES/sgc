<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class BondPole extends Model
{
    /**
     * @var string
     */
    protected $table = 'bond_pole';

    /**
     * @var string
     */
    protected $primaryKey = 'bond_id';

    /**
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'bond_id',
        'pole_id',
    ];

    // ==================== Relationships ====================

    /**
     * @return BelongsTo<Bond, BondPole>
     */
    public function bond(): BelongsTo
    {
        return $this->belongsTo(Bond::class, 'bond_id', 'id');
    }

    /**
     * @return BelongsTo<Pole, BondPole>
     */
    public function pole(): BelongsTo
    {
        return $this->belongsTo(Pole::class, 'pole_id', 'id');
    }
}
