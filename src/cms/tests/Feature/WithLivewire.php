<?php

declare(strict_types=1);

namespace Tests\Feature;

use Livewire\Features\SupportTesting\Testable;
use Livewire\Livewire;

trait WithLivewire
{
    /**
     * @param array<string, mixed> $parameters
     */
    final public function createLivewireTestable(string $componentName, array $parameters = []): Testable
    {
        return Livewire::test($componentName, $parameters);
    }
}
