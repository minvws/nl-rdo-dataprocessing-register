<?php

declare(strict_types=1);

namespace App\Models\Builders;

use App\Models\Organisation;
use App\Models\Snapshot;
use Illuminate\Database\Eloquent\Builder;

/**
 * @extends Builder<Snapshot>
 */
class SnapshotBuilder extends Builder
{
    public function orderByVersion(): self
    {
        return $this->orderBy('version', 'desc');
    }

    public function whereOrganisation(Organisation $organisation): self
    {
        return $this->whereBelongsTo($organisation);
    }
}
