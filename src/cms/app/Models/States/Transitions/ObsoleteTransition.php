<?php

declare(strict_types=1);

namespace App\Models\States\Transitions;

use App\Models\Snapshot;
use App\Models\States\Snapshot\Obsolete;
use Carbon\CarbonImmutable;

class ObsoleteTransition extends StateTransition
{
    public function handle(): Snapshot
    {
        $this->snapshot->replaced_at = CarbonImmutable::now();
        $this->transitionToState(Obsolete::class);

        return $this->snapshot;
    }
}
