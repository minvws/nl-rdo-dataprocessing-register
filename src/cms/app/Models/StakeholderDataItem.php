<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasOrganisation;
use App\Models\Concerns\HasUuidAsKey;
use App\Models\Concerns\IsSortable;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string $id
 * @property string|null $import_id
 * @property string|null $description
 * @property string|null $collection_purpose
 * @property string|null $retention_period
 * @property bool $is_source_stakeholder
 * @property string|null $source_description
 * @property bool $is_stakeholder_mandatory
 * @property string|null $stakeholder_consequences
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property CarbonImmutable|null $deleted_at
 * @property int $sort
 *
 * @property-read Collection<int, Stakeholder> $stakeholders
 */
class StakeholderDataItem extends Model
{
    use IsSortable;
    use HasFactory;
    use HasOrganisation;
    use HasUuidAsKey;
    use SoftDeletes;

    protected $casts = [
        'id' => 'string',
        'is_source_stakeholder' => 'boolean',
        'is_stakeholder_mandatory' => 'boolean',
    ];

    protected $fillable = [
        'import_id',

        'description',
        'collection_purpose',
        'retention_period',
        'is_source_stakeholder',
        'source_description',
        'is_stakeholder_mandatory',
        'stakeholder_consequences',
        'sort',

        'created_at',
        'updated_at',
    ];

    /**
     * @return BelongsToMany<Stakeholder, $this>
     */
    public function stakeholders(): BelongsToMany
    {
        return $this->belongsToMany(Stakeholder::class);
    }
}
