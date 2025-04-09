<?php

declare(strict_types=1);

namespace App\Providers;

use App\Services\EntityNumberService;
use Illuminate\Support\ServiceProvider;

class EntityNumberServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->when(EntityNumberService::class)
            ->needs('$entityNumberModelTypes')
            ->giveConfig('entity-number.model_types');
    }
}
