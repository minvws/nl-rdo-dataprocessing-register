<?php

declare(strict_types=1);

namespace App\Providers;

use App\Actions\Media\MediaContentHasher;
use Illuminate\Support\ServiceProvider;

class FilesystemServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->when(MediaContentHasher::class)
            ->needs('$disk')
            ->giveConfig('media-library.filesystem_disk');
    }
}
