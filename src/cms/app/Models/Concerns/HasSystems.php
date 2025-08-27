<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use App\Attributes\RelatedSnapshotSource;
use App\Models\Contracts\Cloneable;
use App\Models\System;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait HasSystems
{
    final public function initializeHasSystems(): void
    {
        if ($this instanceof Cloneable) {
            $this->addCloneableRelations(['systems']);
        }
    }

    /**
     * @return MorphToMany<System, $this>
     */
    #[RelatedSnapshotSource(System::class)]
    final public function systems(): MorphToMany
    {
        return $this->morphToMany(System::class, 'system_relatable')->withTimestamps();
    }
}
