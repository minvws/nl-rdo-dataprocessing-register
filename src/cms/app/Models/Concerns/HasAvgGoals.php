<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use App\Models\Avg\AvgGoal;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Webmozart\Assert\Assert;

trait HasAvgGoals
{
    public function initializeHasAvgGoals(): void
    {
        Assert::methodExists($this, 'addCloneableRelations');

        $this->addCloneableRelations(['avgGoals']);
    }

    /**
     * @return MorphToMany<AvgGoal, $this>
     */
    public function avgGoals(): MorphToMany
    {
        return $this->morphToMany(AvgGoal::class, 'avg_goal_relatable')->withTimestamps();
    }
}
