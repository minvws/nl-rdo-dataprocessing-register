<?php

declare(strict_types=1);

namespace App\Routing;

use Illuminate\Routing\UrlGenerator as IlluminateUrlGenerator;

use function is_string;

class UrlGenerator extends IlluminateUrlGenerator
{
    /**
     * @param string|false $fallback
     */
    // phpcs:ignore SlevomatCodingStandard.TypeHints.ParameterTypeHint
    public function previous($fallback = false): string
    {
        $url = $this->getPreviousUrlFromSession();

        if (is_string($url)) {
            return $url;
        }

        if (is_string($fallback)) {
            return $this->to($fallback);
        }

        return $this->to('/');
    }
}
