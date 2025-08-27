<?php

declare(strict_types=1);

namespace App\Filament\Infolists\Components\Section;

use Closure;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Illuminate\Contracts\Support\Htmlable;
use Webmozart\Assert\Assert;

use function __;

class UserSection extends Section
{
    public static function make(Htmlable|array|Closure|string|null $heading = null): static
    {
        $defaultHeading = __('user.model_singular');
        Assert::string($defaultHeading);

        return parent::make($heading ?? $defaultHeading)
            ->schema([
                TextEntry::make('name')
                    ->label(__('user.name')),
                TextEntry::make('email')
                    ->label(__('user.email')),
            ]);
    }
}
