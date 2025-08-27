<?php

declare(strict_types=1);

use App\Enums\Authorization\Role;

return [
    Role::CHIEF_PRIVACY_OFFICER->value => 'Chief privacy officer',
    Role::COUNSELOR->value => 'Raadpleger',
    Role::DATA_PROTECTION_OFFICIAL->value => 'Functionaris Gegevensbescherming',
    Role::FUNCTIONAL_MANAGER->value => 'Functioneel beheerder',
    Role::INPUT_PROCESSOR->value => 'Invoerder',
    Role::INPUT_PROCESSOR_DATABREACH->value => 'Invoerder Datalakken',
    Role::MANDATE_HOLDER->value => 'Mandaathouder',
    Role::PRIVACY_OFFICER->value => 'Privacy Officer',
];
