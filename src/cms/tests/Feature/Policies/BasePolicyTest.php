<?php

declare(strict_types=1);

namespace Tests\Feature\Repositories;

use App\Models\User;
use App\Policies\BasePolicy;
use App\Services\AuthorizationService;

use function expect;
use function it;

it('denies all actions by default', function (string $method): void {
    $customPolicy = new class ($this->app->get(AuthorizationService::class)) extends BasePolicy
    {
    };

    $user = User::factory()->create();

    expect($customPolicy->$method($user))
        ->toBeFalse();
})->with([
    'viewAny',
    'view',
    'create',
    'update',
    'delete',
    'restore',
    'forceDelete',
]);
