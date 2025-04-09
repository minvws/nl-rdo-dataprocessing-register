<?php

declare(strict_types=1);

namespace App\Filament\Actions\Exports;

use App\Enums\YesNoUnknown;
use App\Services\DateFormatService;
use Carbon\CarbonInterface;
use Filament\Actions\Exports\ExportColumn as FilamentExportColumn;

use function is_bool;

class ExportColumn extends FilamentExportColumn
{
    public function formatState(mixed $state): mixed
    {
        if (is_bool($state)) {
            return $this->formatBoolean($state);
        }

        if ($state instanceof CarbonInterface) {
            return $this->formatDate($state);
        }

        return parent::formatState($state);
    }

    private function formatBoolean(bool $state): string
    {
        return $state ? YesNoUnknown::YES->value : YesNoUnknown::NO->value;
    }

    private function formatDate(CarbonInterface $state): string
    {
        return $state->format(DateFormatService::FORMAT_DATE_TIME);
    }
}
