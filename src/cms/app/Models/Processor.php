<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Avg\AvgProcessorProcessingRecord;
use App\Models\Avg\AvgResponsibleProcessingRecord;
use App\Models\Concerns\HasAddress;
use App\Models\Concerns\HasOrganisation;
use App\Models\Concerns\HasSnapshots;
use App\Models\Concerns\HasUuidAsKey;
use App\Models\Contracts\SnapshotSource;
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
 * @property string $email
 * @property string $phone
 * @property string|null $import_id
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property CarbonImmutable|null $deleted_at
 *
 * @property-read Address|null $address
 * @property-read Collection<int, AvgProcessorProcessingRecord> $avgProcessorProcessingRecords
 * @property-read Collection<int, AvgResponsibleProcessingRecord> $avgResponsibleProcessingRecords
 * @property-read Collection<int, Snapshot> $snapshots
 * @property-read Collection<int, WpgProcessingRecord> $wpgProcessingRecords
 */
class Processor extends Model implements SnapshotSource
{
    use HasAddress;
    use HasFactory;
    use HasOrganisation;
    use HasSnapshots;
    use HasUuidAsKey;
    use SoftDeletes;

    protected $casts = [
        'address_id' => 'string',
        'id' => 'string',
        'meta' => 'array',
    ];

    protected $fillable = [
        'address_id',

        'name',
        'email',
        'phone',
        'import_id',
        'meta',

        'created_at',
        'updated_at',
    ];

    /**
     * @return MorphToMany<AvgProcessorProcessingRecord, $this>
     */
    public function avgProcessorProcessingRecords(): MorphToMany
    {
        return $this->morphedByMany(AvgProcessorProcessingRecord::class, 'processor_relatable');
    }

    /**
     * @return MorphToMany<AvgResponsibleProcessingRecord, $this>
     */
    public function avgResponsibleProcessingRecords(): MorphToMany
    {
        return $this->morphedByMany(AvgResponsibleProcessingRecord::class, 'processor_relatable');
    }

    /**
     * @return MorphToMany<WpgProcessingRecord, $this>
     */
    public function wpgProcessingRecords(): MorphToMany
    {
        return $this->morphedByMany(WpgProcessingRecord::class, 'processor_relatable');
    }
}
