<?php

declare(strict_types=1);

namespace App\Filament\Forms\Components\Section;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class InformationBlockSection extends Section
{
    public static function makeCollapsible(string $heading, string $info, ?string $extraInfo = null): static
    {
        $informationBlockSection = parent::make($heading);

        if ($extraInfo === null) {
            return $informationBlockSection
                ->schema([
                    self::makePlaceholderWithHtmlString($info, $heading),
                ]);
        }

        return $informationBlockSection
            ->description(self::makeHtmlString($info))
            ->schema([
                self::makePlaceholderWithHtmlString($extraInfo, $heading),
            ])
            ->collapsed();
    }

    private static function makePlaceholderWithHtmlString(string $info, string $heading): TextEntry
    {
        return TextEntry::make('information_block_' . Str::slug($heading))
            ->hiddenLabel()
            ->state(self::makeHtmlString($info));
    }

    private static function makeHtmlString(string $input): HtmlString
    {
        return new HtmlString($input);
    }
}
