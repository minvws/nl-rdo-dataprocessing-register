<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use App\Models\Wpg\WpgGoal;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Webmozart\Assert\Assert;

trait HasWpgGoals
{
    public function initializeHasWpgGoals(): void
    {
        Assert::methodExists($this, 'addCloneableRelations');

        $this->addCloneableRelations(['wpgGoals']);
    }

    /**
     * @return MorphToMany<WpgGoal, $this>
     */
    public function wpgGoals(): MorphToMany
    {
        return $this->morphToMany(WpgGoal::class, 'wpg_goal_relatable')->withTimestamps();
    }
}
