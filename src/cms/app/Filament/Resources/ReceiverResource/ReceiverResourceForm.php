<?php

declare(strict_types=1);

namespace App\Filament\Resources\ReceiverResource;

use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Schema;

use function __;

class ReceiverResourceForm
{
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components(self::getSchema());
    }

    /**
     * @return array<Component>
     */
    public static function getSchema(): array
    {
        return [
            Textarea::make('description')
                ->label(__('receiver.description'))
                ->required()
                ->maxLength(255),
        ];
    }
}
