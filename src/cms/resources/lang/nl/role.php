<?php

declare(strict_types=1);

use App\Enums\Authorization\Role;

return [
    // global
    Role::CHIEF_PRIVACY_OFFICER->value => 'Chief privacy officer',
    Role::FUNCTIONAL_MANAGER->value => 'Functioneel beheerder',

    // organisation
    Role::COUNSELOR->value => 'Raadpleger',
    Role::DATA_PROTECTION_OFFICIAL->value => 'Functionaris Gegevensbescherming',
    Role::INPUT_PROCESSOR->value => 'Invoerder',
    Role::MANDATE_HOLDER->value => 'Mandaathouder',
    Role::PRIVACY_OFFICER->value => 'Privacy Officer',
];
