<?php

declare(strict_types=1);

namespace App\Filament\Resources\AlgorithmRecordResource\Pages;

use App\Filament\Pages\EntityNumberCreateRecord;
use App\Filament\Resources\AlgorithmRecordResource;
use App\Filament\Resources\Pages\Concerns\HasDraftAutosave;

class CreateAlgorithmRecord extends EntityNumberCreateRecord
{
    use HasDraftAutosave;

    protected static string $resource = AlgorithmRecordResource::class;
}
