<?php

declare(strict_types=1);

namespace App\Enums;

enum RegisterLayout: string
{
    case STEPS = 'steps';
    case ONE_PAGE = 'one_page';
}
