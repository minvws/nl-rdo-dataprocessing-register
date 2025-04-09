<?php

declare(strict_types=1);

namespace App\Filament\Resources\ReceiverResource\Pages;

use App\Filament\Pages\CreateRecord;
use App\Filament\Resources\ReceiverResource;

class CreateReceiver extends CreateRecord
{
    protected static string $resource = ReceiverResource::class;
}
