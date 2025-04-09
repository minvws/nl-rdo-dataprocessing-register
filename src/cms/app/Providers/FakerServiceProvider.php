<?php

declare(strict_types=1);

namespace App\Providers;

use App\Config\Config;
use App\Faker\DateTimeProvider;
use App\Faker\ImportProvider;
use App\Faker\MarkupProvider;
use App\Faker\SnapshotProvider;
use Faker\Factory;
use Faker\Generator;
use Illuminate\Support\ServiceProvider;

use function sprintf;

class FakerServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $locale = Config::string('app.faker_locale');
        $concreteClassClosure = static function () use ($locale): Generator {
            $faker = Factory::create($locale);
            $faker->addProvider(new DateTimeProvider($faker));
            $faker->addProvider(new ImportProvider($faker));
            $faker->addProvider(new MarkupProvider($faker));
            $faker->addProvider(new SnapshotProvider($faker));

            return $faker;
        };

        $generator = Generator::class;
        $this->app->singleton($generator, $concreteClassClosure);

        // Bind the same instantation closure to the class name with locale suffix for `fake()` in Pest.
        $generatorWithLocale = sprintf('%s:%s', Generator::class, $locale);
        $this->app->singleton($generatorWithLocale, $concreteClassClosure);
    }
}
