<?php

declare(strict_types=1);

use Filament\Facades\Filament;
use Tests\Helpers\Model\OrganisationTestHelper;

it('makes the navigation panel collapsible', function (): void {
    expect(Filament::getPanel('admin')->isSidebarCollapsibleOnDesktop())->toBeTrue();
});

it('renders a control to collapse the navigation panel', function (): void {
    $organisation = OrganisationTestHelper::create();

    $content = $this->asFilamentOrganisationUser($organisation)
        ->get(sprintf('%s/avg-responsible-processing-record-services', $organisation->slug))
        ->assertOk()
        ->content();

    $collapseControls = [];
    preg_match_all('#<button[^>]*x-on:click="\$store\.sidebar\.close\(\)"[^>]*>#', $content, $collapseControls);

    expect($collapseControls[0])->toHaveCount(2);
});

it('closes the navigation panel with the escape key', function (): void {
    $organisation = OrganisationTestHelper::create();

    $this->asFilamentOrganisationUser($organisation)
        ->get(sprintf('%s/avg-responsible-processing-record-services', $organisation->slug))
        ->assertOk()
        ->assertSee('x-on:keydown.escape.window', escape: false)
        ->assertSee('$store.sidebar.close()', escape: false);
});
