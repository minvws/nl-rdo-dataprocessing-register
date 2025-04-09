<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use App\Attributes\RelatedSnapshotSource;
use App\Models\Responsible;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Webmozart\Assert\Assert;

trait HasResponsibles
{
    public function initializeHasResponsibles(): void
    {
        Assert::methodExists($this, 'addCloneableRelations');

        $this->addCloneableRelations(['responsibles']);
    }

    /**
     * @return MorphToMany<Responsible, $this>
     */
    #[RelatedSnapshotSource(Responsible::class)]
    public function responsibles(): MorphToMany
    {
        return $this->morphToMany(Responsible::class, 'responsible_relatable')->withTimestamps();
    }
}
