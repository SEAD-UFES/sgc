<?php

namespace App\Models;

use App\Enums\GrantTypes;
use App\Models\Filters\RoleFilter;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
     * @var bool
     */
    public $incrementing = true;

    /**
     * @var array<int, string>
     */
    public static $sortable = [
        'id',
        'name',
        'description',
        'grant_value',
        'grant_type',
        'created_at',
        'updated_at',
    ];

    /**
     * @var array<int, string>
     */
    public static $acceptedFilters = [
        'nameContains',
        'descriptionContains',
        'grantvalueExactly',
        'grantvalueBigOrEqu',
        'grantvalueLowOrEqu',
        'grantTypeNameContains',
    ];

    /**
     * @var string
     */
    protected $table = 'roles';

    /**
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'grant_value',
        'grant_type',
    ];

    // ==================== Casts ====================

    protected $casts = [
        'grant_type' => GrantTypes::class,
    ];

    /**
     * @var array<int, string>
     *
     * @phpstan-ignore-next-line
     */
    private static $whiteListFilter = ['*'];

    // ==================== Accessors ====================

    /**
     * @return float
     */
    public function getGrantValueAttribute(): float
    {
        return $this->attributes['grant_value'] / 100;
    }

    // ==================== Relationships ====================

    /**
     * @return HasMany<Bond>
     */
    public function bonds(): HasMany
    {
        return $this->hasMany(Bond::class);
    }

    /**
     * @return HasMany<Applicant>
     */
    public function applicants(): HasMany
    {
        return $this->hasMany(Applicant::class);
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
