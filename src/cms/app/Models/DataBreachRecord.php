<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Avg\AvgProcessorProcessingRecord;
use App\Models\Avg\AvgResponsibleProcessingRecord;
use App\Models\Concerns\HasDocuments;
use App\Models\Concerns\HasEntityNumber;
use App\Models\Concerns\HasFgRemark;
use App\Models\Concerns\HasOrganisation;
use App\Models\Concerns\HasResponsibles;
use App\Models\Concerns\HasUuidAsKey;
use App\Models\Concerns\IsCloneable;
use App\Models\Contracts\Cloneable;
use App\Models\Contracts\EntityNumerable;
use App\Models\Wpg\WpgProcessingRecord;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string $id
 * @property string $name
 * @property string $type
 * @property CarbonImmutable|null $reported_at
 * @property bool $ap_reported
 * @property CarbonImmutable|null $discovered_at
 * @property CarbonImmutable|null $started_at
 * @property CarbonImmutable|null $ended_at
 * @property CarbonImmutable|null $ap_reported_at
 * @property CarbonImmutable|null $completed_at
 * @property string|null $nature_of_incident
 * @property string|null $nature_of_incident_other
 * @property string|null $summary
 * @property string|null $involved_people
 * @property array|null $personal_data_categories
 * @property string|null $personal_data_categories_other
 * @property array|null $personal_data_special_categories
 * @property string|null $estimated_risk
 * @property string|null $measures
 * @property bool $reported_to_involved
 * @property array|null $reported_to_involved_communication
 * @property string|null $reported_to_involved_communication_other
 * @property bool $fg_reported
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property CarbonImmutable|null $deleted_at
 *
 * @property-read Collection<int, AvgProcessorProcessingRecord> $avgProcessorProcessingRecords
 * @property-read Collection<int, AvgResponsibleProcessingRecord> $avgResponsibleProcessingRecords
 * @property-read Collection<int, Document> $documents
 * @property-read Collection<int, Responsible> $responsibles
 * @property-read Collection<int, WpgProcessingRecord> $wpgProcessingRecords
 */
class DataBreachRecord extends Model implements EntityNumerable, Cloneable
{
    use HasDocuments;
    use HasEntityNumber;
    use HasFactory;
    use HasFgRemark;
    use HasOrganisation;
    use HasResponsibles;
    use HasUuidAsKey;
    use IsCloneable;
    use SoftDeletes;

    /** @var array<string, string> $casts */
    protected $casts = [
        'ap_reported' => 'boolean',

        'reported_at' => 'date',
        'discovered_at' => 'date',
        'started_at' => 'date',
        'ended_at' => 'date',
        'ap_reported_at' => 'date',
        'completed_at' => 'date',

        'personal_data_categories' => 'array',
        'personal_data_special_categories' => 'array',
        'reported_to_involved_communication' => 'array',
    ];

    /** @var array<int, string> $fillable */
    protected $fillable = [
        'name',
        'type',
        'reported_at',
        'ap_reported',

        'discovered_at',
        'started_at',
        'ended_at',
        'ap_reported_at',
        'completed_at',

        'nature_of_incident',
        'nature_of_incident_other',
        'summary',
        'involved_people',
        'personal_data_categories',
        'personal_data_categories_other',
        'personal_data_special_categories',
        'estimated_risk',
        'measures',
        'reported_to_involved',
        'reported_to_involved_communication',
        'reported_to_involved_communication_other',
        'fg_reported',
    ];

    /**
     * @return MorphToMany<AvgProcessorProcessingRecord, $this>
     */
    public function avgProcessorProcessingRecords(): MorphToMany
    {
        return $this->morphedByMany(AvgProcessorProcessingRecord::class, 'data_breach_record_relatable');
    }

    /**
     * @return MorphToMany<AvgResponsibleProcessingRecord, $this>
     */
    public function avgResponsibleProcessingRecords(): MorphToMany
    {
        return $this->morphedByMany(AvgResponsibleProcessingRecord::class, 'data_breach_record_relatable');
    }

    /**
     * @return MorphToMany<WpgProcessingRecord, $this>
     */
    public function wpgProcessingRecords(): MorphToMany
    {
        return $this->morphedByMany(WpgProcessingRecord::class, 'data_breach_record_relatable');
    }
}
