<?php

declare(strict_types=1);

namespace App\Models\Algorithm;

use App\Collections\Algorithm\AlgorithmRecordCollection;
use App\Collections\Algorithm\AlgorithmStatusCollection;
use App\Models\LookupListModel;
use Database\Factories\Algorithm\AlgorithmStatusFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property-read AlgorithmRecordCollection $algorithmRecords
 */
class AlgorithmStatus extends LookupListModel
{
    /** @use HasFactory<AlgorithmStatusFactory> */
    use HasFactory;

    protected static string $collectionClass = AlgorithmStatusCollection::class;

    /**
     * @return HasMany<AlgorithmRecord, $this>
     */
    public function algorithmRecords(): HasMany
    {
        return $this->hasMany(AlgorithmRecord::class);
    }
}
