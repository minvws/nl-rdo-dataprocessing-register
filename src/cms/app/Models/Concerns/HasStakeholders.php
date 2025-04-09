<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use App\Models\Stakeholder;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Webmozart\Assert\Assert;

trait HasStakeholders
{
    public function initializeHasStakeholders(): void
    {
        Assert::methodExists($this, 'addCloneableRelations');

        $this->addCloneableRelations(['stakeholders']);
    }

    /**
     * @return MorphToMany<Stakeholder, $this>
     */
    public function stakeholders(): MorphToMany
    {
        return $this->morphToMany(Stakeholder::class, 'stakeholder_relatable')->withTimestamps();
    }
}
