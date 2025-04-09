<?php

declare(strict_types=1);

namespace App\Enums\Authorization;

enum Role: string
{
    // global
    case FUNCTIONAL_MANAGER = 'functional-manager';

    // organisation
    case CHIEF_PRIVACY_OFFICER = 'chief-privacy-officer';
    case COUNSELOR = 'counselor';
    case DATA_PROTECTION_OFFICIAL = 'data-protection-official';
    case INPUT_PROCESSOR = 'input-processor';
    case MANDATE_HOLDER = 'mandate-holder';
    case PRIVACY_OFFICER = 'privacy-officer';
}
