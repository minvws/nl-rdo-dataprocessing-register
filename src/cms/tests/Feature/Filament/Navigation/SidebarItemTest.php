<?php

declare(strict_types=1);

use Tests\Helpers\Model\OrganisationTestHelper;

it('marks the navigation item of the current page with aria-current', function (): void {
    $organisation = OrganisationTestHelper::create();

    $content = $this->asFilamentOrganisationUser($organisation)
        ->get(sprintf('%s/avg-responsible-processing-record-services', $organisation->slug))
        ->assertOk()
        ->content();

    $links = [];
    preg_match_all('#<a[^>]*aria-current="page"[^>]*>#s', $content, $links);

    expect($links[0])->not->toBeEmpty();

    foreach ($links[0] as $link) {
        expect($link)->toContain(sprintf('%s/avg-responsible-processing-record-services', $organisation->slug));
    }
});
