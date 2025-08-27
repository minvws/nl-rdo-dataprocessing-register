<?php

declare(strict_types=1);

namespace Feature\Config;

use App\Config\Environment;
use Illuminate\Support\Facades\App;

use function fake;
use function it;

it('is development', function (): void {
    $isEnvironment = fake()->boolean();

    App::expects('environment')
        ->with(['dev', 'development', 'local'])
        ->andReturn($isEnvironment);

    $this->assertSame($isEnvironment, Environment::isDevelopment());
});

it('is production', function (): void {
    $isEnvironment = fake()->boolean();

    App::expects('environment')
        ->with(['production'])
        ->andReturn($isEnvironment);

    $this->assertSame($isEnvironment, Environment::isProduction());
});

it('is testing', function (): void {
    $isEnvironment = fake()->boolean();

    App::expects('environment')
        ->with(['test', 'testing'])
        ->andReturn($isEnvironment);

    $this->assertSame($isEnvironment, Environment::isTesting());
});
