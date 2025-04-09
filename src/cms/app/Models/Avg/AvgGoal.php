<?php

declare(strict_types=1);

namespace App\Models\Avg;

use App\Models\Concerns\HasOrganisation;
use App\Models\Concerns\HasUuidAsKey;
use App\Models\Concerns\IsSortable;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string $id
 * @property string $goal
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property string|null $import_id
 * @property string|null $avg_goal_legal_base
 * @property CarbonImmutable|null $deleted_at
 * @property string|null $remarks
 * @property int $sort
 *
 * @property-read Collection<int, AvgProcessorProcessingRecord> $avgProcessorProcessingRecords
 * @property-read Collection<int, AvgResponsibleProcessingRecord> $avgResponsibleProcessingRecords
 */
class AvgGoal extends Model
{
    use HasFactory;
    use HasOrganisation;
    use HasUuidAsKey;
    use IsSortable;
    use SoftDeletes;

    protected $casts = [
        'id' => 'string',
    ];

    protected $fillable = [
        'avg_goal_legal_base',

        'goal',
        'sort',
        'remarks',

        'import_id',
    ];

    /**
     * @return MorphToMany<AvgProcessorProcessingRecord, $this>
     */
    public function avgProcessorProcessingRecords(): MorphToMany
    {
        return $this->morphedByMany(AvgProcessorProcessingRecord::class, 'avg_goal_relatable');
    }

    /**
     * @return MorphToMany<AvgResponsibleProcessingRecord, $this>
     */
    public function avgResponsibleProcessingRecords(): MorphToMany
    {
        return $this->morphedByMany(AvgResponsibleProcessingRecord::class, 'avg_goal_relatable');
    }

    public function getDisplayName(): string
    {
        return $this->goal;
    }
}
