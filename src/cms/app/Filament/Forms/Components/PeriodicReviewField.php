<?php

declare(strict_types=1);

namespace App\Filament\Forms\Components;

use App\Facades\Authentication;
use App\Filament\Forms\Components\DatePicker\DatePicker;
use Carbon\CarbonImmutable;

use function __;

class PeriodicReviewField
{
    public static function make(): DatePicker
    {
        return DatePicker::make('review_at')
            ->label(__('general.review_at'))
            ->required()
            ->hintIcon('heroicon-o-information-circle', __('general.review_at_hint'))
            ->default(static function (): CarbonImmutable {
                $organisation = Authentication::organisation();
                return CarbonImmutable::today()->addMonths($organisation->review_at_default_in_months);
            });
    }
}
