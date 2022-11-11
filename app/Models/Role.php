<?php

namespace App\Models;

use App\Enums\GrantTypes;
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
     * @var string
     */
    protected $table = 'roles';

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
        'grant_value',
        'grant_type',
    ];

    // /**
    //  * @var array<int, string>
    //  */
    // public $sortable = ['id', 'name', 'description', 'grant_value', 'created_at', 'updated_at'];

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

    // /**
    //  * @var array<int, string>
    //  *
    //  * @phpstan-ignore-next-line
    //  */
    // private static $whiteListFilter = ['*'];

    // ==================== Casts ====================

    protected $casts = [
        'grant_type' => GrantTypes::class,
    ];

    // ===============================================


    /* public function bonds()
    {
        return $this->hasMany(User::class);
    } */

    // ==================== Accessors ====================

    // /**
    //  * @return Attribute<float, float>
    //  */
    // protected function grantValueReal(): Attribute
    // {
    //     return Attribute::make(
    //         get: fn ($value) => $this->grant_value / 100,
    //     );
    // }

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
