<?php

declare(strict_types=1);

namespace App\Filament\Resources\ResponsibleLegalEntityResource;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;

use function __;

class ResponsibleLegalEntityResourceForm
{
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label(__('responsible_legal_entity.name'))
                    ->required()
                    ->maxLength(255),
            ]);
    }
}
