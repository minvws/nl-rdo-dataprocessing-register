<?php

declare(strict_types=1);

namespace App\Models\Wpg;

use App\Collections\Wpg\WpgProcessingRecordCollection;
use App\Collections\Wpg\WpgProcessingRecordServiceCollection;
use App\Models\LookupListModel;
use Database\Factories\Wpg\WpgProcessingRecordServiceFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property-read WpgProcessingRecordCollection $wpgProcessingRecords
 */
class WpgProcessingRecordService extends LookupListModel
{
    /** @use HasFactory<WpgProcessingRecordServiceFactory> */
    use HasFactory;

    protected static string $collectionClass = WpgProcessingRecordServiceCollection::class;
    protected $table = 'wpg_processing_record_service';

    /**
     * @return HasMany<WpgProcessingRecord, $this>
     */
    public function wpgProcessingRecords(): HasMany
    {
        return $this->hasMany(WpgProcessingRecord::class);
    }
}
