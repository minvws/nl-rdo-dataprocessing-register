<?php

declare(strict_types=1);

use App\Import\Factories\AbstractFactory;
use App\Models\Avg\AvgResponsibleProcessingRecord;
use App\Services\Snapshot\SnapshotFactory;
use Carbon\CarbonImmutable;
use Carbon\Exceptions\InvalidFormatException;
use Tests\Helpers\ConfigHelper;

it('creates no snapshot if no configured state found', function (): void {
    ConfigHelper::set('import.value_converters.snapshot_state', []);
    $id = fake()->uuid();

    $factory = new class extends AbstractFactory {
        public function test(string $id): void
        {
            $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::factory()->create([
                'id' => $id,
            ]);

            $this->createSnapshot($avgResponsibleProcessingRecord, 1, 'unknown', app(SnapshotFactory::class));
        }
    };
    $factory->test($id);

    $avgResponsibleProcessingRecord = AvgResponsibleProcessingRecord::query()
        ->where(['id' => $id])
        ->firstOrFail();

    expect($avgResponsibleProcessingRecord->snapshots()->count())
        ->toBe(0);
});

it('returns string when input is array', function (): void {
    $factory = new class extends AbstractFactory {
        public function test(): ?string
        {
            return $this->toString([fake()->word()]);
        }
    };

    $value = $factory->test();
    expect($value)
        ->toBeString();
});

it('returns null when input is null', function (): void {
    $factory = new class extends AbstractFactory {
        public function test(): ?string
        {
            return $this->toStringOrNull(null);
        }
    };

    $value = $factory->test();
    expect($value)
        ->toBeNull();
});

it('returns imploded string when input is an array', function (): void {
    $factory = new class extends AbstractFactory {
        public function test(): ?string
        {
            return $this->toStringOrNull(['foo', 'bar']);
        }
    };

    $value = $factory->test();
    expect($value)
        ->toBe("foo\nbar");
});

it('returns array if input is null', function (): void {
    $factory = new class extends AbstractFactory {
        public function test(): array
        {
            return $this->toArray(null);
        }
    };

    $value = $factory->test();
    expect($value)
        ->toBe([]);
});

it('returns null for description when input is null', function (): void {
    $factory = new class extends AbstractFactory {
        public function test(): ?string
        {
            return $this->getDescriptionFromOptions(null, fake()->word());
        }
    };

    $value = $factory->test();
    expect($value)
        ->toBeNull();
});

it('throws exception on invalid date-format', function (): void {
    $factory = new class extends AbstractFactory {
        public function test(): CarbonImmutable
        {
            return $this->toCarbon('invalid');
        }
    };

    $factory->test();
})->expectException(InvalidFormatException::class);
