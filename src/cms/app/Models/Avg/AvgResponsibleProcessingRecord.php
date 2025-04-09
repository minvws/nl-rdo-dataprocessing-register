<?php

declare(strict_types=1);

namespace App\Models\Avg;

use App\Enums\CoreEntityDataCollectionSource;
use App\Models\Concerns\HasAvgGoals;
use App\Models\Concerns\HasChildren;
use App\Models\Concerns\HasContactPersons;
use App\Models\Concerns\HasDataBreachRecords;
use App\Models\Concerns\HasDocuments;
use App\Models\Concerns\HasEntityNumber;
use App\Models\Concerns\HasFgRemark;
use App\Models\Concerns\HasOrganisation;
use App\Models\Concerns\HasProcessors;
use App\Models\Concerns\HasReceivers;
use App\Models\Concerns\HasRemarks;
use App\Models\Concerns\HasResponsibles;
use App\Models\Concerns\HasSnapshots;
use App\Models\Concerns\HasStakeholders;
use App\Models\Concerns\HasSystems;
use App\Models\Concerns\HasTags;
use App\Models\Concerns\HasUuidAsKey;
use App\Models\Concerns\IsCloneable;
use App\Models\Concerns\IsPublishable;
use App\Models\ContactPerson;
use App\Models\Contracts\Cloneable;
use App\Models\Contracts\EntityNumerable;
use App\Models\Contracts\Publishable;
use App\Models\Contracts\SnapshotSource;
use App\Models\DataBreachRecord;
use App\Models\Document;
use App\Models\Processor;
use App\Models\Receiver;
use App\Models\Responsible;
use App\Models\Snapshot;
use App\Models\Stakeholder;
use App\Models\System;
use App\Models\Tag;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string $id
 * @property string $name
 * @property bool $outside_eu
 * @property bool $decision_making
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property string|null $import_id
 * @property string|null $import_number
 * @property CarbonImmutable|null $review_at
 * @property bool $has_processors
 * @property bool $has_security
 * @property bool $has_systems
 * @property string|null $responsibility_distribution
 * @property string|null $pseudonymization
 * @property bool $measures_implemented
 * @property bool $other_measures
 * @property string|null $outside_eu_description
 * @property string|null $outside_eu_protection_level_description
 * @property string|null $avg_responsible_processing_record_service_id
 * @property bool $has_pseudonymization
 * @property string|null $logic
 * @property string|null $importance_consequences
 * @property CarbonImmutable|null $deleted_at
 * @property string|null $country
 * @property string|null $country_other
 * @property bool $outside_eu_protection_level
 * @property bool $geb_dpia_executed
 * @property bool $geb_dpia_automated
 * @property bool $geb_dpia_large_scale_processing
 * @property bool $geb_dpia_large_scale_monitoring
 * @property bool $geb_dpia_list_required
 * @property bool $geb_dpia_criteria_wp248
 * @property bool $geb_dpia_high_risk_freedoms
 * @property string|null $parent_id
 * @property CarbonImmutable|null $public_from
 * @property string|null $measures_description
 * @property CoreEntityDataCollectionSource $data_collection_source
 * @property string|null $published_at
 *
 * @property-read Collection<int, AvgGoal> $avgGoals
 * @property-read AvgResponsibleProcessingRecordService|null $avgResponsibleProcessingRecordService
 * @property-read Collection<int, AvgResponsibleProcessingRecord> $children
 * @property-read Collection<int, ContactPerson> $contactPersons
 * @property-read Collection<int, Document> $documents
 * @property-read AvgResponsibleProcessingRecord|null $parent
 * @property-read Collection<int, Processor> $processors
 * @property-read Collection<int, Receiver> $receivers
 * @property-read Collection<int, Responsible> $responsibles
 * @property-read Collection<int, Snapshot> $snapshots
 * @property-read Collection<int, Stakeholder> $stakeholders
 * @property-read Collection<int, System> $systems
 * @property-read Collection<int, Tag> $tags
 * @property-read Collection<int, DataBreachRecord> $dataBreachRecords
 */
class AvgResponsibleProcessingRecord extends Model implements EntityNumerable, SnapshotSource, Publishable, Cloneable
{
    use HasAvgGoals;
    use HasChildren;
    use HasContactPersons;
    use HasDataBreachRecords;
    use HasDocuments;
    use HasEntityNumber;
    use HasFactory;
    use HasFgRemark;
    use HasOrganisation;
    use HasProcessors;
    use HasReceivers;
    use HasRemarks;
    use HasResponsibles;
    use HasSnapshots;
    use HasStakeholders;
    use HasSystems;
    use HasTags;
    use HasUuidAsKey;
    use IsPublishable;
    use IsCloneable;
    use SoftDeletes;

    protected $casts = [
        'decision_making' => 'bool',
        'has_processors' => 'bool',
        'has_security' => 'bool',
        'has_systems' => 'bool',
        'id' => 'string',
        'measures' => 'bool',
        'other_measures' => 'bool',
        'outside_eu' => 'bool',
        'review_at' => 'date',
        'has_pseudonymization' => 'bool',
        'outside_eu_protection_level' => 'bool',
        'geb_dpia_executed' => 'bool',
        'geb_dpia_automated' => 'bool',
        'geb_dpia_large_scale_processing' => 'bool',
        'geb_dpia_large_scale_monitoring' => 'bool',
        'geb_dpia_list_required' => 'bool',
        'geb_dpia_criteria_wp248' => 'bool',
        'geb_dpia_high_risk_freedoms' => 'bool',
        'public_from' => 'datetime',
        'data_collection_source' => CoreEntityDataCollectionSource::class,
    ];
    protected $fillable = [
        'avg_responsible_processing_record_service_id',
        'parent_id',

        'data_collection_source',
        'name',
        'service',
        'responsibility_distribution',
        'has_pseudonymization',
        'pseudonymization',
        'measures_implemented',
        'other_measures',
        'measures_description',
        'outside_eu',
        'outside_eu_description',
        'outside_eu_protection_level',
        'outside_eu_protection_level_description',
        'country',
        'country_other',
        'decision_making',
        'logic',
        'importance_consequences',
        'import_id',
        'review_at',
        'has_processors',
        'has_security',
        'has_systems',
        'geb_dpia_executed',
        'geb_dpia_automated',
        'geb_dpia_large_scale_processing',
        'geb_dpia_large_scale_monitoring',
        'geb_dpia_list_required',
        'geb_dpia_criteria_wp248',
        'geb_dpia_high_risk_freedoms',
        'public_from',
    ];

    /**
     * @return BelongsTo<AvgResponsibleProcessingRecordService, $this>
     */
    public function avgResponsibleProcessingRecordService(): BelongsTo
    {
        return $this->belongsTo(AvgResponsibleProcessingRecordService::class);
    }
}
