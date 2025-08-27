<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use App\Attributes\RelatedSnapshotSource;
use App\Models\Contracts\Cloneable;
use App\Models\Responsible;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait HasResponsibles
{
    final public function initializeHasResponsibles(): void
    {
        if ($this instanceof Cloneable) {
            $this->addCloneableRelations(['responsibles']);
        }
    }

    /**
     * @return MorphToMany<Responsible, $this>
     */
    #[RelatedSnapshotSource(Responsible::class)]
    final public function responsibles(): MorphToMany
    {
        return $this->morphToMany(Responsible::class, 'responsible_relatable')->withTimestamps();
    }
}
