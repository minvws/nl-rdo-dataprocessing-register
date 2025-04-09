<?php

declare(strict_types=1);

namespace App\Filament\Resources\OrganisationSnapshotApprovalResource\Pages;

use App\Filament\Resources\OrganisationSnapshotApprovalResource;
use Filament\Resources\Pages\ListRecords;

class ListOrganisationSnapshotApprovalItems extends ListRecords
{
    protected static string $resource = OrganisationSnapshotApprovalResource::class;
}
