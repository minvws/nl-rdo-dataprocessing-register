<?php

declare(strict_types=1);

use App\Filament\Resources\UserResource;
use Tests\Helpers\Model\UserTestHelper;

it('loads the view page', function (): void {
    $user = UserTestHelper::create();

    $this->asFilamentUser()
        ->get(UserResource::getUrl('view', ['record' => $user]))
        ->assertSuccessful();
});
