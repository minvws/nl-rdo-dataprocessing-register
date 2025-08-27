<?php

declare(strict_types=1);

namespace App\Filament\RelationManagers;

use App\Filament\Resources\ContactPersonResource;
use Illuminate\Database\Eloquent\Model;

use function __;

class ProcessingRecordContactPersonRelationManager extends RelationManager
{
    protected static string $languageFile = 'contact_person';
    protected static string $relationship = 'contactPersons';
    protected static string $resource = ContactPersonResource::class;

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('contact_person.form_title_contact_persons');
    }
}
