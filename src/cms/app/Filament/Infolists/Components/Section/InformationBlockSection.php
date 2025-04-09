<?php

declare(strict_types=1);

namespace App\Filament\Infolists\Components\Section;

use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Illuminate\Support\HtmlString;

class InformationBlockSection extends Section
{
    public static function makeCollapsible(string $heading, string $info, ?string $extraInfo = null, string $name = 'name'): static
    {
        $informationBlockSection = parent::make($heading);

        if ($extraInfo === null) {
            return $informationBlockSection
                ->schema([
                    self::makePlaceholderWithHtmlString($info, $name),
                ]);
        }

        return $informationBlockSection
            ->description(self::makeHtmlString($info))
            ->schema([
                self::makePlaceholderWithHtmlString($extraInfo, $name),
            ])
            ->collapsed();
    }

    private static function makePlaceholderWithHtmlString(string $info, string $name): TextEntry
    {
        return TextEntry::make($name)
            ->label('')
            ->formatStateUsing(static function () use ($info): HtmlString {
                return self::makeHtmlString($info);
            });
    }

    private static function makeHtmlString(string $input): HtmlString
    {
        return new HtmlString($input);
    }
}
