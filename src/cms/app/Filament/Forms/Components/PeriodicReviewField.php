<?php

declare(strict_types=1);

namespace App\Filament\Forms\Components;

use App\Filament\Forms\Components\DatePicker\DatePicker;

use function __;

class PeriodicReviewField
{
    public static function make(): DatePicker
    {
        return DatePicker::make('review_at')
            ->label(__('general.review_at'))
            ->hintIcon('heroicon-o-information-circle', __('general.review_at_hint'));
    }
}
