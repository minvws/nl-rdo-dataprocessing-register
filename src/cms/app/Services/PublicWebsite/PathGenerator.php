<?php

declare(strict_types=1);

namespace App\Services\PublicWebsite;

use App\Models\Contracts\Publishable;
use App\Models\Organisation;
use Illuminate\Support\Str;

use function sprintf;

readonly class PathGenerator
{
    public function getIndexPath(): string
    {
        return '_index.html';
    }

    public function getOrganisationPath(Organisation $organisation): string
    {
        return sprintf('organisatie/%s/_index.html', $organisation->slug);
    }

    public function getOrganisationIndexPath(): string
    {
        return 'organisatie/_index.html';
    }

    public function getOrganisationPosterPath(Organisation $organisation): string
    {
        return sprintf('organisatie/%s/poster.jpeg', $organisation->slug);
    }

    public function getPublishableIndexPath(Organisation $organisation): string
    {
        return sprintf('organisatie/%s/verwerkingen/_index.html', $organisation->slug);
    }

    public function getPublishablePath(Publishable $publishable): string
    {
        return sprintf(
            'organisatie/%s/verwerkingen/%s.html',
            $publishable->getOrganisation()->slug,
            Str::slug($publishable->getPublicIdentifier()),
        );
    }
}
