<?php

declare(strict_types=1);

namespace App\Providers;

use App\Services\Snapshot\SnapshotDataMarkdownRenderer;
use Illuminate\Support\ServiceProvider;

class SnapshotDataServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->when(SnapshotDataMarkdownRenderer::class)
            ->needs('$renderTemplates')
            ->giveConfig('snapshot-data.render-templates');
    }
}
