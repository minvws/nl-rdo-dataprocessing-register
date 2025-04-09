<?php

declare(strict_types=1);

namespace App\Filament\Forms\Components\Section;

use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Illuminate\Support\HtmlString;

class InformationBlockSection extends Section
{
    public static function makeCollapsible(string $heading, string $info, ?string $extraInfo = null): static
    {
        $informationBlockSection = parent::make($heading);

        if ($extraInfo === null) {
            return $informationBlockSection
                ->schema([
                    self::makePlaceholderWithHtmlString($info),
                ]);
        }

        return $informationBlockSection
            ->description(self::makeHtmlString($info))
            ->schema([
                self::makePlaceholderWithHtmlString($extraInfo),
            ])
            ->collapsed();
    }

    private static function makePlaceholderWithHtmlString(string $info): Placeholder
    {
        return Placeholder::make('')
            ->hiddenLabel()
            ->content(self::makeHtmlString($info));
    }

    private static function makeHtmlString(string $input): HtmlString
    {
        return new HtmlString($input);
    }
}
