<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\Organisation;
use Closure;
use Filament\Facades\Filament;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Webmozart\Assert\Assert;

class IPAllowFilter
{
    /**
     * @throws AuthorizationException
     */
    public function handle(Request $request, Closure $next): Response
    {
        $organisation = Filament::getTenant();
        Assert::nullOrIsInstanceOf($organisation, Organisation::class);

        if ($organisation === null) {
            return $next($request);
        }

        $ip = $request->ip();
        Assert::stringNotEmpty($ip);

        if ($organisation->isIPAllowed($ip)) {
            return $next($request);
        }

        throw new AuthorizationException('Access denied');
    }
}
