<?php

namespace App\Models;

use App\ModelFilters\RoleFilter;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Kyslik\ColumnSortable\Sortable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Role extends Model
{
    use HasFactory;
    use Sortable;
    use RoleFilter;
    use Filterable;
    use LogsActivity;

    /**
     * @var array<int, string>
     */
    public $sortable = ['id', 'name', 'description', 'grant_value', 'created_at', 'updated_at'];

    /**
     * @var array<int, string>
     */
    public static $accepted_filters = [
        'nameContains',
        'descriptionContains',
        'grantvalueExactly',
        'grantvalueBigOrEqu',
        'grantvalueLowOrEqu',
        'grantTypeNameContains',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'grant_value',
        'grant_type_id',
    ];

    /**
     * @var array<int, string>
     *
     * @phpstan-ignore-next-line
     */
    private static $whiteListFilter = ['*'];

    /* public function bonds()
    {
        return $this->hasMany(User::class);
    } */

    /**
     * @return BelongsTo<GrantType, Role>
     */
    public function grantType(): BelongsTo
    {
        return $this->belongsTo(GrantType::class);
    }

    /**
     * @return HasMany<Bond>
     */
    public function bonds(): HasMany
    {
        return $this->hasMany(Bond::class);
    }

    /**
     * @return HasMany<Approved>
     */
    public function approveds(): HasMany
    {
        return $this->hasMany(Approved::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*'])
            ->logExcept(['updated_at'])
            ->dontLogIfAttributesChangedOnly(['updated_at'])
            ->logOnlyDirty();
    }

    /**
     * @return Attribute<float, float>
     */
    protected function grantValueReal(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->grant_value / 100,
        );
    }
}
