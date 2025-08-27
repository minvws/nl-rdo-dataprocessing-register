<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Config\Config;
use App\Models\Organisation;
use App\Types\IPRanges;
use Closure;
use Filament\Facades\Filament;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Webmozart\Assert\Assert;

use function sprintf;

class IPAllowFilter
{
    /**
     * @throws AuthorizationException
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $organisation = Filament::getTenant();
        Assert::nullOrIsInstanceOf($organisation, Organisation::class);

        if ($organisation === null) {
            return $next($request);
        }

        $ip = $request->ip();
        Assert::stringNotEmpty($ip);

        $ipRangesString = sprintf('%s,%s', Config::stringOrNull('app.allowed_ips'), $organisation->allowed_ips);
        $ipRanges = IPRanges::make($ipRangesString);
        if ($ipRanges->contains($ip)) {
            return $next($request);
        }

        throw new AuthorizationException('Access denied');
    }
}
