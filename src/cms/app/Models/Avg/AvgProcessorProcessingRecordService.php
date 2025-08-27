<?php

declare(strict_types=1);

namespace App\Models\Avg;

use App\Collections\Avg\AvgProcessorProcessingRecordCollection;
use App\Collections\Avg\AvgProcessorProcessingRecordServiceCollection;
use App\Models\LookupListModel;
use Database\Factories\Avg\AvgProcessorProcessingRecordServiceFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property-read AvgProcessorProcessingRecordCollection $avgProcessorProcessingRecords
 */
class AvgProcessorProcessingRecordService extends LookupListModel
{
    /** @use HasFactory<AvgProcessorProcessingRecordServiceFactory> */
    use HasFactory;

    protected static string $collectionClass = AvgProcessorProcessingRecordServiceCollection::class;
    protected $table = 'avg_processor_processing_record_service';

    /**
     * @return HasMany<AvgProcessorProcessingRecord, $this>
     */
    public function avgProcessorProcessingRecords(): HasMany
    {
        return $this->hasMany(AvgProcessorProcessingRecord::class);
    }
}
