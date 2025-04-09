<?php

declare(strict_types=1);

namespace App\Filament\RelationManagers;

use App\Filament\Resources\ReceiverResource;

class ReceiverRelationManager extends RelationManager
{
    protected static string $languageFile = 'receiver';
    protected static string $relationship = 'receivers';
    protected static string $resource = ReceiverResource::class;
}
