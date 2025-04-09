<?php

declare(strict_types=1);

namespace App\Models\Avg;

use App\Models\Concerns\HasUuidAsKey;
use App\Models\LookupListModel;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string $id
 * @property string $name
 * @property bool $enabled
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property CarbonImmutable|null $deleted_at
 *
 * @property-read Collection<int, AvgProcessorProcessingRecord> $avgProcessorProcessingRecords
 */
class AvgProcessorProcessingRecordService extends LookupListModel
{
    use HasFactory;
    use HasUuidAsKey;
    use SoftDeletes;

    protected $casts = [
        'id' => 'string',
    ];

    protected $fillable = [
        'name',
        'enabled',
    ];

    protected $table = 'avg_processor_processing_record_service';

    /**
     * @return HasMany<AvgProcessorProcessingRecord, $this>
     */
    public function avgProcessorProcessingRecords(): HasMany
    {
        return $this->hasMany(AvgProcessorProcessingRecord::class);
    }
}
