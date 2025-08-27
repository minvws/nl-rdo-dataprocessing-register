<?php

declare(strict_types=1);

namespace App\Enums;

enum StateColor: string
{
    case DANGER = 'danger';
    case GRAY = 'gray';
    case INFO = 'info';
    case PRIMARY = 'primary';
    case SUCCESS = 'success';
    case WARNING = 'warning';
}
