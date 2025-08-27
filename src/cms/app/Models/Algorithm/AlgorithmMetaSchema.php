<?php

declare(strict_types=1);

namespace App\Models\Algorithm;

use App\Collections\Algorithm\AlgorithmMetaSchemaCollection;
use App\Collections\Algorithm\AlgorithmRecordCollection;
use App\Models\LookupListModel;
use Database\Factories\Algorithm\AlgorithmMetaSchemaFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property-read AlgorithmRecordCollection $algorithmRecords
 */
class AlgorithmMetaSchema extends LookupListModel
{
    /** @use HasFactory<AlgorithmMetaSchemaFactory> */
    use HasFactory;

    protected static string $collectionClass = AlgorithmMetaSchemaCollection::class;

    /**
     * @return HasMany<AlgorithmRecord, $this>
     */
    public function algorithmRecords(): HasMany
    {
        return $this->hasMany(AlgorithmRecord::class);
    }
}
