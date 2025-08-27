<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Application;

use function sprintf;

trait CreatesApplication
{
    final public function createApplication(): Application
    {
        $app = require sprintf('%s/%s', __DIR__, '/../bootstrap/app.php');

        /** @var Kernel $kernel */
        $kernel = $app->make(Kernel::class);
        $kernel->bootstrap();

        return $app;
    }
}
