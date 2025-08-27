<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use App\Collections\StakeholderCollection;
use App\Models\Contracts\Cloneable;
use App\Models\Stakeholder;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

/**
 * @property-read StakeholderCollection $stakeholders
 */
trait HasStakeholders
{
    final public function initializeHasStakeholders(): void
    {
        if ($this instanceof Cloneable) {
            $this->addCloneableRelations(['stakeholders']);
        }
    }

    /**
     * @return MorphToMany<Stakeholder, $this>
     */
    final public function stakeholders(): MorphToMany
    {
        return $this->morphToMany(Stakeholder::class, 'stakeholder_relatable')->withTimestamps();
    }
}
