<?php

declare(strict_types=1);

namespace App\Filament\Resources\AlgorithmPublicationCategoryResource\Pages;

use App\Filament\Resources\AlgorithmPublicationCategoryResource;
use App\Filament\Resources\Pages\ListLookupListRecords;

class ListAlgorithmPublicationCategories extends ListLookupListRecords
{
    protected static string $resource = AlgorithmPublicationCategoryResource::class;
}
