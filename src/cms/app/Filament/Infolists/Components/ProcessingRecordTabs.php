<?php

declare(strict_types=1);

namespace App\Filament\Infolists\Components;

use Closure;
use Filament\Schemas\Components\Tabs;
use Illuminate\Contracts\Support\Htmlable;

class ProcessingRecordTabs extends Tabs
{
    public static function make(string|Htmlable|Closure|null $label = null): static
    {
        return parent::make($label)
            ->vertical()
            ->persistTabInQueryString();
    }
}
