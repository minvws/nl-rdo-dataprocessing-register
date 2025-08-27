<?php

declare(strict_types=1);

namespace App\Models\States\Transitions;

use App\Models\Snapshot;
use App\Models\States\Snapshot\Established;

class EstablishedTransition extends StateTransition
{
    public function handle(): Snapshot
    {
        $this->transitionToState(Established::class);

        return $this->snapshot;
    }
}
