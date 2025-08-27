<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use App\Models\Contracts\Cloneable;
use App\Models\Wpg\WpgGoal;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait HasWpgGoals
{
    final public function initializeHasWpgGoals(): void
    {
        if ($this instanceof Cloneable) {
            $this->addCloneableRelations(['wpgGoals']);
        }
    }

    /**
     * @return MorphToMany<WpgGoal, $this>
     */
    final public function wpgGoals(): MorphToMany
    {
        return $this->morphToMany(WpgGoal::class, 'wpg_goal_relatable')->withTimestamps();
    }
}
