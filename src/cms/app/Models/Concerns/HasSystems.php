<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use App\Attributes\RelatedSnapshotSource;
use App\Models\System;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Webmozart\Assert\Assert;

trait HasSystems
{
    public function initializeHasSystems(): void
    {
        Assert::methodExists($this, 'addCloneableRelations');

        $this->addCloneableRelations(['systems']);
    }

    /**
     * @return MorphToMany<System, $this>
     */
    #[RelatedSnapshotSource(System::class)]
    public function systems(): MorphToMany
    {
        return $this->morphToMany(System::class, 'system_relatable')->withTimestamps();
    }
}
