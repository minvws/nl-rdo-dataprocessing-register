<?php

declare(strict_types=1);

namespace App\Filament\Resources\LookupListResource;

use App\Filament\Infolists\Components\ToggleEntry;
use Filament\Infolists\Components\Component;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;

use function __;

class LookupListResourceInfolist
{
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make()
                    ->schema(self::getSchema()),
            ]);
    }

    /**
     * @return array<Component>
     */
    public static function getSchema(): array
    {
        return [
            Grid::make()
                ->schema([
                    TextEntry::make('name')
                        ->label(__('general.name')),
                    ToggleEntry::make('enabled')
                        ->label(__('general.enabled')),
                ]),
        ];
    }
}
