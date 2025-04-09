<?php

declare(strict_types=1);

namespace App\Filament\RelationManagers;

use App\Filament\Resources\ContactPersonResource;

class ContactPersonRelationManager extends RelationManager
{
    protected static string $languageFile = 'contact_person';
    protected static string $relationship = 'contactPersons';
    protected static string $resource = ContactPersonResource::class;
}
