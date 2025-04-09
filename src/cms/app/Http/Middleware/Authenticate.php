<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

use function route;

class Authenticate extends Middleware
{
    protected function redirectTo(Request $request): ?string
    {
        // @codeCoverageIgnoreStart
        return $request->expectsJson() ? null : route('filament.admin.auth.login');
        // @codeCoverageIgnoreEnd
    }
}
