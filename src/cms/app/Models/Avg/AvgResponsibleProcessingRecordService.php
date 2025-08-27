<?php

declare(strict_types=1);

namespace App\Models\Avg;

use App\Collections\Avg\AvgResponsibleProcessingRecordCollection;
use App\Collections\Avg\AvgResponsibleProcessingRecordServiceCollection;
use App\Models\LookupListModel;
use Database\Factories\Avg\AvgResponsibleProcessingRecordServiceFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property-read AvgResponsibleProcessingRecordCollection $avgResponsibleProcessingRecords
 */
class AvgResponsibleProcessingRecordService extends LookupListModel
{
    /** @use HasFactory<AvgResponsibleProcessingRecordServiceFactory> */
    use HasFactory;

    protected static string $collectionClass = AvgResponsibleProcessingRecordServiceCollection::class;
    protected $table = 'avg_responsible_processing_record_service';

    /**
     * @return HasMany<AvgResponsibleProcessingRecord, $this>
     */
    public function avgResponsibleProcessingRecords(): HasMany
    {
        return $this->hasMany(AvgResponsibleProcessingRecord::class);
    }
}
