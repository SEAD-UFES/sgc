<?php

namespace App\Models;

use App\ModelFilters\PoleFilter;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Kyslik\ColumnSortable\Sortable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Pole extends Model
{
    use HasFactory;
    use Sortable;
    use PoleFilter;
    use Filterable;
    use LogsActivity;

    /**
     * @var string
     */
    protected $table = 'poles';

    /**
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * @var bool
     */
    public $incrementing = true;

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
    public static $accepted_filters = [
        'nameContains',
        'descriptionContains',
    ];

    // /**
    //  * @var array<int, string>
    //  */
    // public $sortable = ['id', 'name', 'description', 'created_at', 'updated_at'];

    // /**
    //  * @var array<int, string>
    //  *
    //  * @phpstan-ignore-next-line
    //  */
    // private static $whiteListFilter = ['*'];

    // ==================== Relationships ====================

    /**
     * @return BelongsToMany<Bond, Pole>
     */
    public function bonds(): BelongsToMany
    {
        return $this->belongsToMany(Bond::class, 'bond_pole', 'pole_id', 'bond_id');
    }

    /**
     * @return HasMany<Applicant>
     */
    public function applicants(): HasMany
    {
        return $this->hasMany(Applicant::class, 'pole_id', 'id');
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
