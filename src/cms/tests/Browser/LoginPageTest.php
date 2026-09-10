<?php

declare(strict_types=1);

it('renders the login page in a real browser without javascript errors', function (): void {
    visit('/login')
        ->assertNoJavaScriptErrors()
        ->assertPresent('form');
});
