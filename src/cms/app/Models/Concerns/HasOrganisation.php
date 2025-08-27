<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use App\Components\Uuid\UuidInterface;
use App\Models\Casts\UuidCast;
use App\Models\Organisation;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property UuidInterface $organisation_id
 *
 * @property-read Organisation $organisation
 */
trait HasOrganisation
{
    final public function initializeHasOrganisation(): void
    {
        $this->mergeCasts(['organisation_id' => UuidCast::class]);
        $this->mergeFillable(['organisation_id']);
    }

    /**
     * @return BelongsTo<Organisation, $this>
     */
    final public function organisation(): BelongsTo
    {
        return $this->belongsTo(Organisation::class);
    }

    final public function getOrganisation(): Organisation
    {
        return $this->organisation;
    }
}
