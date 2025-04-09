<?php

declare(strict_types=1);

namespace App\Filament\Resources\PersonalSnapshotApprovalResource\Pages;

use App\Filament\Resources\PersonalSnapshotApprovalResource;
use Filament\Resources\Pages\ListRecords;

class ListPersonalSnapshotApprovalItems extends ListRecords
{
    protected static string $resource = PersonalSnapshotApprovalResource::class;
}
