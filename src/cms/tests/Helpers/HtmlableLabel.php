<?php

declare(strict_types=1);

namespace Tests\Helpers;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;

enum HtmlableLabel: string implements HasLabel
{
    case EXAMPLE = 'example';

    public function getLabel(): Htmlable
    {
        return new HtmlString('<strong>example</strong>');
    }
}
