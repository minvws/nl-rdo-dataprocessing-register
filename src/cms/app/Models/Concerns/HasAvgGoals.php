<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use App\Collections\Avg\AvgGoalCollection;
use App\Models\Avg\AvgGoal;
use App\Models\Contracts\Cloneable;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

/**
 * @property-read AvgGoalCollection $avgGoals
 */
trait HasAvgGoals
{
    final public function initializeHasAvgGoals(): void
    {
        if ($this instanceof Cloneable) {
            $this->addCloneableRelations(['avgGoals']);
        }
    }

    /**
     * @return MorphToMany<AvgGoal, $this>
     */
    final public function avgGoals(): MorphToMany
    {
        return $this->morphToMany(AvgGoal::class, 'avg_goal_relatable')->withTimestamps();
    }
}
