<?php

declare(strict_types=1);

namespace App\Filament\RelationManagers;

use App\Filament\Resources\UserResource;
use Illuminate\Database\Eloquent\Model;

use function __;

class ProcessingRecordUsersRelationManager extends RelationManager
{
    protected static string $languageFile = 'user';
    protected static string $relationship = 'users';
    protected static string $resource = UserResource::class;

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('contact_person.form_title_users');
    }
}
