<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use App\Models\Contracts\Cloneable;
use App\Models\DataBreachRecord;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait HasDataBreachRecords
{
    final public function initializeHasDataBreachRecords(): void
    {
        if ($this instanceof Cloneable) {
            $this->addCloneableRelations(['dataBreachRecords']);
        }
    }

    /**
     * @return MorphToMany<DataBreachRecord, $this>
     */
    final public function dataBreachRecords(): MorphToMany
    {
        return $this->morphToMany(DataBreachRecord::class, 'data_breach_record_relatable');
    }
}
