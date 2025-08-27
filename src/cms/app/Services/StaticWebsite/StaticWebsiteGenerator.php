<?php

declare(strict_types=1);

namespace App\Services\StaticWebsite;

interface StaticWebsiteGenerator
{
    /**
     * @throws BuildException
     */
    public function generate(): void;
}
