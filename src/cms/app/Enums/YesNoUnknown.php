<?php

declare(strict_types=1);

namespace App\Enums;

enum YesNoUnknown: string
{
    case YES = 'yes';
    case NO = 'no';
    case UNKNOWN = 'unknown';
}
