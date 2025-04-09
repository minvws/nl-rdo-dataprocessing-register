<?php

declare(strict_types=1);

namespace App\Filament\Tables;

use App\Filament\TenantScoped;
use Filament\Tables\Filters\SelectFilter;

use function __;

class ContactPersonFilter extends SelectFilter
{
    public static function make(?string $name = null): static
    {
        return parent::make($name ?? 'contactPerson')
            ->label(__('contact_person.model_plural'))
            ->relationship('contactPersons', 'name', TenantScoped::getAsClosure())
            ->searchable()
            ->multiple();
    }
}
