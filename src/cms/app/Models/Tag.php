<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Avg\AvgResponsibleProcessingRecord;
use App\Models\Concerns\HasOrganisation;
use App\Models\Concerns\HasUuidAsId;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property array $name
 * @property array $slug
 * @property int|null $order_column
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property CarbonImmutable|null $deleted_at
 *
 * @property-read mixed $translations
 * @property-read Collection<int, AvgResponsibleProcessingRecord> $avgResponsibleProcessingRecords
 */
class Tag extends Model
{
    use HasFactory;
    use HasOrganisation;
    use HasUuidAsId;
    use SoftDeletes;

    /** @var array<int, string> $fillable */
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
