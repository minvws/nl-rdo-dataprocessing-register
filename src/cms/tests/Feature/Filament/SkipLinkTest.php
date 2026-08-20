<?php

declare(strict_types=1);

use Tests\Helpers\Model\OrganisationTestHelper;

// A link to the content lets keyboard users pass the blocks that repeat on every page (WCAG 2.4.1).
it('starts every page with a link to the content', function (): void {
    $organisation = OrganisationTestHelper::create();

    $content = $this->asFilamentOrganisationUser($organisation)
        ->get(sprintf('%s/avg-responsible-processing-record-services', $organisation->slug))
        ->assertOk()
        ->assertSee(__('general.skip_to_content'))
        ->content();

    $skipLinkPosition = strpos($content, 'href="#main-content"');
    $topbarPosition = strpos($content, 'fi-topbar');

    expect($skipLinkPosition)->not->toBeFalse()
        ->and($topbarPosition)->not->toBeFalse()
        ->and($skipLinkPosition)->toBeLessThan($topbarPosition)
        ->and($content)->toContain('id="main-content"');
});

it('gives the login page a target for the link to the content', function (): void {
    $this->get('/login')
        ->assertOk()
        ->assertSee('id="main-content"', escape: false);
});
