<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Organisation;
use App\Models\Principal;
use App\Models\User;
use App\Models\UserGlobalRole;
use App\Models\UserOrganisationRole;
use Filament\Facades\Filament;
use Illuminate\Support\Collection;
use Webmozart\Assert\Assert;
use Webmozart\Assert\InvalidArgumentException;

class AuthenticationService
{
    private ?Principal $principal = null;

    /**
     * @throws InvalidArgumentException
     */
    public function organisation(): Organisation
    {
        $organisation = Filament::getTenant();
        Assert::isInstanceOf($organisation, Organisation::class);

        return $organisation;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function user(): User
    {
        $user = Filament::auth()->user();
        Assert::isInstanceOf($user, User::class);

        return $user;
    }

    public function getPrincipal(): Principal
    {
        if ($this->principal === null) {
            $roles = [];

            foreach ($this->getGlobalRoles() as $globalRole) {
                $roles[] = $globalRole->role;
            }

            foreach ($this->getOrganisationRoles() as $organisationRole) {
                $roles[] = $organisationRole->role;
            }

            $this->principal = new Principal($roles);
        }

        return $this->principal;
    }

    /**
     * @return Collection<int, UserGlobalRole>
     */
    private function getGlobalRoles(): Collection
    {
        try {
            return $this->user()->globalRoles;
        } catch (InvalidArgumentException) {
            return new Collection();
        }
    }

    /**
     * @return Collection<int, UserOrganisationRole>
     */
    private function getOrganisationRoles(): Collection
    {
        try {
            /** @var Collection<int, UserOrganisationRole> $userOrganisationRoles */
            $userOrganisationRoles = $this->user()->organisationRoles()
                ->where(['organisation_id' => $this->organisation()->id])
                ->get();
        } catch (InvalidArgumentException) {
            return new Collection();
        }

        return $userOrganisationRoles;
    }
}
