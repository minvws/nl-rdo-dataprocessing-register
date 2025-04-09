<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Facades\Authentication;
use App\Models\Organisation;
use App\Models\UserOrganisationRole;
use Filament\Facades\Filament;
use Filament\Panel;
use Illuminate\Http\RedirectResponse;
use Throwable;
use Webmozart\Assert\Assert;

use function abort;
use function redirect;
use function route;

class RedirectToTenantController
{
    public function __invoke(): RedirectResponse
    {
        try {
            $user = Authentication::user();
        } catch (Throwable) {
            return redirect(route('filament.admin.auth.login'));
        }

        /** @var UserOrganisationRole|null $organisationRole */
        $organisationRole = $user->organisationRoles()->first();

        if ($organisationRole !== null) {
            $tenant = $organisationRole->organisation;
            Assert::isInstanceOf($tenant, Organisation::class);

            return $this->redirectToOrganisation($tenant);
        }

        $organisation = $user->organisations->first();
        if ($organisation === null) {
            abort(403);
        }

        if ($user->globalRoles->isNotEmpty()) {
            return $this->redirectToOrganisation($organisation);
        }

        abort(403);
    }

    private function redirectToOrganisation(Organisation $organisation): RedirectResponse
    {
        $panel = Filament::getCurrentPanel();
        Assert::isInstanceOf($panel, Panel::class);

        $url = $panel->getUrl($organisation);
        Assert::string($url);

        return redirect($url);
    }
}
