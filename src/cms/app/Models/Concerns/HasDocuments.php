<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use App\Models\Contracts\Cloneable;
use App\Models\Document;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait HasDocuments
{
    final public function initializeHasDocuments(): void
    {
        if ($this instanceof Cloneable) {
            $this->addCloneableRelations(['documents']);
        }
    }

    /**
     * @return MorphToMany<Document, $this>
     */
    final public function documents(): MorphToMany
    {
        return $this->morphToMany(Document::class, 'document_relatable')->withTimestamps();
    }
}
