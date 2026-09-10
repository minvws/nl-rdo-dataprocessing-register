<?php

declare(strict_types=1);

use App\Enums\RouteName;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Route as RouteFacade;
use Webmozart\Assert\Assert;

it('boots the panel on the routes that render outside of it', function (RouteName $routeName): void {
    $route = RouteFacade::getRoutes()->getByName($routeName->value);
    Assert::isInstanceOf($route, Route::class);

    expect($route->gatherMiddleware())
        ->toContain('panel:admin');
})->with([
    RouteName::PASSWORDLESS_LOGIN_VALIDATE_CONSUME,
    RouteName::PASSWORDLESS_LOGIN_VALIDATE_CONFIRM,
    RouteName::SNAPSHOT_SIGN_LOGIN_BATCH_OPEN,
    RouteName::SNAPSHOT_SIGN_LOGIN_BATCH_LOGIN,
    RouteName::SNAPSHOT_SIGN_LOGIN_SINGLE_OPEN,
    RouteName::SNAPSHOT_SIGN_LOGIN_SINGLE_LOGIN,
    RouteName::TWO_FACTOR_AUTHENTICATION_REQUEST,
]);
