<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\OrganisationUser;
use App\Models\UserOrganisationRole;

class OrganisationUserObserver
{
    public function deleted(OrganisationUser $organisationUser): void
    {
        UserOrganisationRole::query()
            ->where('organisation_id', $organisationUser->organisation->id)
            ->where('user_id', $organisationUser->user->id)
            ->delete();
    }
}
