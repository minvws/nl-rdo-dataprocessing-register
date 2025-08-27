<?php

declare(strict_types=1);

namespace App\Models;

use App\Collections\StakeholderCollection;
use App\Collections\StakeholderDataItemCollection;
use App\Models\Concerns\HasOrganisation;
use App\Models\Concerns\HasSoftDeletes;
use App\Models\Concerns\HasTimestamps;
use App\Models\Concerns\HasUuidAsId;
use App\Models\Concerns\IsSortable;
use App\Models\Contracts\TenantAware;
use Database\Factories\StakeholderDataItemFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property string|null $import_id
 * @property string|null $description
 * @property string|null $collection_purpose
 * @property string|null $retention_period
 * @property bool $is_source_stakeholder
 * @property string|null $source_description
 * @property bool $is_stakeholder_mandatory
 * @property string|null $stakeholder_consequences
 * @property int $sort
 *
 * @property-read StakeholderCollection $stakeholders
 */
class StakeholderDataItem extends Model implements TenantAware
{
    /** @use HasFactory<StakeholderDataItemFactory> */
    use HasFactory;
    use HasOrganisation;
    use HasSoftDeletes;
    use HasTimestamps;
    use HasUuidAsId;
    use IsSortable;

    protected static string $collectionClass = StakeholderDataItemCollection::class;
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
    ];

    public function casts(): array
    {
        return [
            'is_source_stakeholder' => 'boolean',
            'is_stakeholder_mandatory' => 'boolean',
        ];
    }

    /**
     * @return BelongsToMany<Stakeholder, $this>
     */
    public function stakeholders(): BelongsToMany
    {
        return $this->belongsToMany(Stakeholder::class);
    }
}
