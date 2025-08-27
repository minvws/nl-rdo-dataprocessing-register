<?php

declare(strict_types=1);

namespace App\Models\Avg;

use App\Collections\Avg\AvgGoalCollection;
use App\Collections\Avg\AvgProcessorProcessingRecordCollection;
use App\Collections\Avg\AvgResponsibleProcessingRecordCollection;
use App\Models\Concerns\HasOrganisation;
use App\Models\Concerns\HasSoftDeletes;
use App\Models\Concerns\HasTimestamps;
use App\Models\Concerns\HasUuidAsId;
use App\Models\Concerns\IsSortable;
use App\Models\Contracts\TenantAware;
use Database\Factories\Avg\AvgGoalFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

/**
 * @property string $goal
 * @property string|null $import_id
 * @property string|null $avg_goal_legal_base
 * @property string|null $remarks
 * @property int $sort
 *
 * @property-read AvgProcessorProcessingRecordCollection $avgProcessorProcessingRecords
 * @property-read AvgResponsibleProcessingRecordCollection $avgResponsibleProcessingRecords
 */
class AvgGoal extends Model implements TenantAware
{
    /** @use HasFactory<AvgGoalFactory> */
    use HasFactory;
    use HasOrganisation;
    use HasSoftDeletes;
    use HasTimestamps;
    use HasUuidAsId;
    use IsSortable;

    protected static string $collectionClass = AvgGoalCollection::class;
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
