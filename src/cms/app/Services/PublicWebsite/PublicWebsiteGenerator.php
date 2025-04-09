<?php

declare(strict_types=1);

namespace App\Services\PublicWebsite;

interface PublicWebsiteGenerator
{
    /**
     * @throws BuildException
     */
    public function generate(): void;
}
