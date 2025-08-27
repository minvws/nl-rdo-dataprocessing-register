<?php

declare(strict_types=1);

namespace App\Import\Factories\Concerns;

use App\Config\Config;

use function in_array;

trait StateHelper
{
    final protected function skipState(string $state): bool
    {
        $statesToSkip = Config::array('import.states_to_skip_import');

        return in_array($state, $statesToSkip, true);
    }
}
