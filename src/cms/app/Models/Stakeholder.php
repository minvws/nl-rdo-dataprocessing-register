<?php

declare(strict_types=1);

namespace App\Models;

use App\Collections\Avg\AvgResponsibleProcessingRecordCollection;
use App\Collections\StakeholderCollection;
use App\Collections\StakeholderDataItemCollection;
use App\Models\Avg\AvgProcessorProcessingRecord;
use App\Models\Avg\AvgResponsibleProcessingRecord;
use App\Models\Concerns\HasOrganisation;
use App\Models\Concerns\HasSoftDeletes;
use App\Models\Concerns\HasTimestamps;
use App\Models\Concerns\HasUuidAsId;
use App\Models\Concerns\IsSortable;
use App\Models\Contracts\TenantAware;
use Database\Factories\StakeholderFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

/**
 * @property string|null $import_id
 * @property string|null $description
 * @property bool $biometric
 * @property bool $faith_or_belief
 * @property bool $genetic
 * @property bool $health
 * @property bool $political_attitude
 * @property bool $race_or_ethnicity
 * @property bool $sexual_life
 * @property bool $trade_association_membership
 * @property bool $criminal_law
 * @property bool $citizen_service_numbers
 * @property int $sort
 * @property string|null $special_collected_data_explanation
 *
 * @property-read AvgResponsibleProcessingRecordCollection $avgResponsibleProcessingRecords
 * @property-read StakeholderDataItemCollection $stakeholderDataItems
 */
class Stakeholder extends Model implements TenantAware
{
    /** @use HasFactory<StakeholderFactory> */
    use HasFactory;
    use HasOrganisation;
    use HasSoftDeletes;
    use HasTimestamps;
    use HasUuidAsId;
    use IsSortable;

    protected static string $collectionClass = StakeholderCollection::class;
    protected $fillable = [
        'import_id',
        'description',
        'biometric',
        'faith_or_belief',
        'genetic',
        'health',
        'political_attitude',
        'race_or_ethnicity',
        'sexual_life',
        'trade_association_membership',
        'criminal_law',
        'citizen_service_numbers',
        'special_collected_data_explanation',
        'created_at',
        'updated_at',
        'sort',
    ];

    /**
     * @return MorphToMany<AvgProcessorProcessingRecord, $this>
     */
    public function avgProcessorProcessingRecords(): MorphToMany
    {
        return $this->morphedByMany(AvgProcessorProcessingRecord::class, 'stakeholder_relatable');
    }

    /**
     * @return MorphToMany<AvgResponsibleProcessingRecord, $this>
     */
    public function avgResponsibleProcessingRecords(): MorphToMany
    {
        return $this->morphedByMany(AvgResponsibleProcessingRecord::class, 'stakeholder_relatable');
    }

    /**
     * @return BelongsToMany<StakeholderDataItem, $this>
     */
    public function stakeholderDataItems(): BelongsToMany
    {
        return $this->belongsToMany(StakeholderDataItem::class);
    }
}
