<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use App\Models\DataBreachRecord;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Webmozart\Assert\Assert;

trait HasDataBreachRecords
{
    public function initializeHasDataBreachRecords(): void
    {
        Assert::methodExists($this, 'addCloneableRelations');

        $this->addCloneableRelations(['dataBreachRecords']);
    }

    /**
     * @return MorphToMany<DataBreachRecord, $this>
     */
    public function dataBreachRecords(): MorphToMany
    {
        return $this->morphToMany(DataBreachRecord::class, 'data_breach_record_relatable');
    }
}
