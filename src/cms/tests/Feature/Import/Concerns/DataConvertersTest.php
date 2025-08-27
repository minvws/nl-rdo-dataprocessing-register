<?php

declare(strict_types=1);

use App\Import\Factories\Concerns\DataConverters;
use Carbon\CarbonImmutable;
use Carbon\Exceptions\InvalidFormatException;
use Tests\Helpers\ConfigTestHelper;

it('returns string when input is array for toString', function (): void {
    $factory = new class {
        use DataConverters;

        public function test(): ?string
        {
            $key = fake()->word();
            return $this->toString([$key => fake()->word()], $key);
        }
    };

    $value = $factory->test();
    expect($value)
        ->toBeString();
});

it('returns null when input is null for toStringOrNull', function (): void {
    $factory = new class {
        use DataConverters;

        public function test(): ?string
        {
            $key = fake()->word();
            return $this->toStringOrNull([$key => null], $key);
        }
    };

    $value = $factory->test();
    expect($value)
        ->toBeNull();
});

it('returns correct value when key is multidimensional for toStringOrNull', function (): void {
    $factory = new class {
        use DataConverters;

        public function test(): ?string
        {
            return $this->toStringOrNull(['foo' => ['bar' => 'baz']], 'foo.bar');
        }
    };

    $value = $factory->test();
    expect($value)
        ->toBe('baz');
});

it('returns imploded string when input is an array', function (): void {
    $factory = new class {
        use DataConverters;

        public function test(): ?string
        {
            $key = fake()->word();
            return $this->toStringOrNull([$key => ['foo', 'bar']], $key);
        }
    };

    $value = $factory->test();
    expect($value)
        ->toBe("foo\nbar");
});

it('returns emptry string when input is null for toImplodedString', function (): void {
    $factory = new class {
        use DataConverters;

        public function test(): ?string
        {
            return $this->toImplodedString(null);
        }
    };

    $value = $factory->test();
    expect($value)
        ->toBe('');
});

it('returns array if input is empty for toArray', function (): void {
    $factory = new class {
        use DataConverters;

        public function test(): array
        {
            return $this->toArray([], fake()->word());
        }
    };

    $value = $factory->test();
    expect($value)
        ->toBe([]);
});

it('returns null for description when input is null for getDescriptionFromOptions', function (): void {
    $factory = new class {
        use DataConverters;

        public function test(): ?string
        {
            return $this->getDescriptionFromOptions(null, fake()->word());
        }
    };

    $value = $factory->test();
    expect($value)
        ->toBeNull();
});

it('returns correct boolean for toBoolean', function (array $config, bool|int|string|null $input, bool $expectedResult): void {
    ConfigTestHelper::set('import.value_converters.boolean_true', $config);

    $factory = new class {
        use DataConverters;

        public function test(bool|int|string|null $input): bool
        {
            $key = fake()->word();
            return $this->toBoolean([$key => $input], $key);
        }
    };

    $value = $factory->test($input);
    expect($value)
        ->toBe($expectedResult);
})->with([
    [['true'], 'true', true],
    [[], true, true],
    [['ja', 'true'], 'true', true],
    [['false'], 'false', true],
    [[1], 1, true],
    [[1], '1', false],
    [['1'], 1, false],
    [[], false, false],
    [[], null, false],
    [['false'], false, false],
    [['ja'], 'true', false],
    [[], 'true', false],
]);

it('throws exception on invalid date-format for toCarbon', function (): void {
    $factory = new class {
        use DataConverters;

        public function test(): CarbonImmutable
        {
            return $this->toCarbon(['key' => 'invalid'], 'key');
        }
    };

    $factory->test();
})->throws(InvalidFormatException::class);
