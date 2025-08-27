<?php

declare(strict_types=1);

namespace App\Models;

use App\Collections\Avg\AvgResponsibleProcessingRecordCollection;
use App\Collections\TagCollection;
use App\Models\Avg\AvgResponsibleProcessingRecord;
use App\Models\Concerns\HasOrganisation;
use App\Models\Concerns\HasSoftDeletes;
use App\Models\Concerns\HasTimestamps;
use App\Models\Concerns\HasUuidAsId;
use App\Models\Contracts\TenantAware;
use Database\Factories\TagFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

/**
 * @property string $name
 *
 * @property-read AvgResponsibleProcessingRecordCollection $avgResponsibleProcessingRecords
 */
class Tag extends Model implements TenantAware
{
    /** @use HasFactory<TagFactory> */
    use HasFactory;
    use HasOrganisation;
    use HasSoftDeletes;
    use HasTimestamps;
    use HasUuidAsId;

    protected static string $collectionClass = TagCollection::class;
    protected $fillable = [
        'name',
    ];

    /**
     * @return MorphToMany<AvgResponsibleProcessingRecord, $this>
     */
    public function avgResponsibleProcessingRecords(): MorphToMany
    {
        return $this->morphedByMany(AvgResponsibleProcessingRecord::class, 'taggable');
    }
}
