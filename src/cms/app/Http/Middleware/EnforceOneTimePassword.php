<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Facades\Authentication;
use App\Facades\Otp;
use App\Filament\Pages\Profile;
use Closure;
use Exception;
use Filament\Facades\Filament;
use Filament\Panel;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Symfony\Component\HttpFoundation\Response;
use Webmozart\Assert\Assert;

use function is_string;
use function redirect;
use function request;
use function route;
use function sprintf;

class EnforceOneTimePassword
{
    /**
     * @throws Exception
     */
    public function handle(Request $request, Closure $next): Response
    {
        $route = $request->route();
        Assert::isInstanceOf($route, Route::class);

        $tenantId = $route->parameter('tenant');
        if (!is_string($tenantId)) {
            return $next($request);
        }

        $user = Authentication::user();
        if (Otp::hasOtpConfirmed($user) && Otp::hasValidSession()) {
            return $next($request);
        }

        if (Otp::hasOtpConfirmed($user) && !Otp::hasValidSession()) {
            return redirect(route('two-factor-authentication', [
                'tenant' => $tenantId,
                'next' => request()->getRequestUri(),
            ]));
        }

        $profilePageRouteName = $this->getProfilePageRouteName();
        if ($request->routeIs($profilePageRouteName)) {
            return $next($request);
        }

        return redirect()->route($profilePageRouteName, ['tenant' => $tenantId]);
    }

    private function getProfilePageRouteName(): string
    {
        $panel = Filament::getCurrentPanel();
        Assert::isInstanceOf($panel, Panel::class);

        return sprintf('filament.%s.pages.%s', $panel->getId(), Profile::getSlug());
    }
}
