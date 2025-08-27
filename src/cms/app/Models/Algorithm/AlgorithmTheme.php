<?php

declare(strict_types=1);

namespace App\Models\Algorithm;

use App\Collections\Algorithm\AlgorithmRecordCollection;
use App\Collections\Algorithm\AlgorithmThemeCollection;
use App\Models\LookupListModel;
use Database\Factories\Algorithm\AlgorithmThemeFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property-read AlgorithmRecordCollection $algorithmRecords
 */
class AlgorithmTheme extends LookupListModel
{
    /** @use HasFactory<AlgorithmThemeFactory> */
    use HasFactory;

    protected static string $collectionClass = AlgorithmThemeCollection::class;

    /**
     * @return HasMany<AlgorithmRecord, $this>
     */
    public function algorithmRecords(): HasMany
    {
        return $this->hasMany(AlgorithmRecord::class);
    }
}
