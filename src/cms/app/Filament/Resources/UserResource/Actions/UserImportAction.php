<?php

declare(strict_types=1);

namespace App\Filament\Resources\UserResource\Actions;

use App\Enums\Authorization\Permission;
use App\Facades\Authorization;
use App\Filament\Imports\UserImporter;
use Filament\Actions\ImportAction;

class UserImportAction extends ImportAction
{
    public static function make(?string $name = null): static
    {
        return parent::make()
            ->importer(UserImporter::class)
            ->visible(Authorization::hasPermission(Permission::USER_IMPORT))
            ->color('gray');
    }
}
