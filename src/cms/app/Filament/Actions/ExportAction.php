<?php

declare(strict_types=1);

namespace App\Filament\Actions;

use App\Config\Config;
use App\Enums\Authorization\Permission;
use App\Facades\Authorization;
use App\Services\DateFormatService;
use Carbon\CarbonImmutable;
use Filament\Actions\ExportAction as FilamentExportAction;
use Illuminate\Support\Str;

use function __;
use function sprintf;

class ExportAction extends FilamentExportAction
{
    public static function make(?string $name = null): static
    {
        return parent::make($name)
            ->label(__('general.export'))
            ->visible(Authorization::hasPermission(Permission::EXPORT))
            ->columnMapping(false)
            ->fileName(static function (): string {
                return sprintf(
                    '%s-%s-export',
                    DateFormatService::toFilename(CarbonImmutable::now()),
                    Str::slug(Config::string('app.name')),
                );
            });
    }
}
