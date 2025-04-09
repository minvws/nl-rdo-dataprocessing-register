<?php

declare(strict_types=1);

it('can run the command', function (): void {
    $this->artisan('document:notifications')
        ->assertSuccessful();
});
