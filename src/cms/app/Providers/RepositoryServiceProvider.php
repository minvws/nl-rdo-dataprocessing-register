<?php

declare(strict_types=1);

namespace App\Providers;

use App\Repositories\AdminLogRepository;
use App\Repositories\DbAdminLogRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(AdminLogRepository::class, DbAdminLogRepository::class);
    }
}
