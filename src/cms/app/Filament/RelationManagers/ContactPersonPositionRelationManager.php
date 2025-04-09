<?php

declare(strict_types=1);

namespace App\Filament\RelationManagers;

use App\Filament\Resources\ContactPersonPositionResource;

class ContactPersonPositionRelationManager extends RelationManager
{
    protected static string $languageFile = 'contact_person_position';
    protected static string $relationship = 'contactPersonPosition';
    protected static string $resource = ContactPersonPositionResource::class;
}
