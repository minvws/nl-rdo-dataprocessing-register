<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use App\Models\Contracts\Cloneable;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait HasTags
{
    final public function initializeHasTags(): void
    {
        if ($this instanceof Cloneable) {
            $this->addCloneableRelations(['tags']);
        }
    }

    /**
     * @return MorphToMany<Tag, $this>
     */
    final public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }
}
