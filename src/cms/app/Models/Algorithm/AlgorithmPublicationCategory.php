<?php

declare(strict_types=1);

namespace App\Models\Algorithm;

use App\Collections\Algorithm\AlgorithmPublicationCategoryCollection;
use App\Collections\Algorithm\AlgorithmRecordCollection;
use App\Models\LookupListModel;
use Database\Factories\Algorithm\AlgorithmPublicationCategoryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property-read AlgorithmRecordCollection $algorithmRecords
 */
class AlgorithmPublicationCategory extends LookupListModel
{
    /** @use HasFactory<AlgorithmPublicationCategoryFactory> */
    use HasFactory;

    protected static string $collectionClass = AlgorithmPublicationCategoryCollection::class;

    /**
     * @return HasMany<AlgorithmRecord, $this>
     */
    public function algorithmRecords(): HasMany
    {
        return $this->hasMany(AlgorithmRecord::class);
    }
}
