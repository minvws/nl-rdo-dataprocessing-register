<?php

declare(strict_types=1);

namespace Tests\Feature\Filament\Resources\AvgProcessorProcessingRecordResource;

use App\Filament\Infolists\InfolistHelper;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;
use Mockery;

use function expect;
use function fake;
use function it;

it('returns false when attribute not available with fieldValuesContainValue', function (): void {
    $modelAttribute = fake()->word();

    $modelMock = Mockery::mock(Model::class);
    $modelMock->shouldReceive('getAttribute')
        ->with($modelAttribute)
        ->andReturn(null);

    $fieldValuesContainValued = InfolistHelper::fieldValuesContainValue($modelAttribute, fake()->word());

    expect($fieldValuesContainValued($modelMock))
        ->toBe(false);
});

it('returns false when attribute not in results with fieldValuesContainValue', function (): void {
    $fieldName = fake()->word();

    $modelMock = Mockery::mock(Model::class);
    $modelMock->shouldReceive('getAttribute')
        ->with($fieldName)
        ->andReturn([fake()->word(), fake()->word()]);

    $fieldValuesContainValued = InfolistHelper::fieldValuesContainValue($fieldName, fake()->uuid());

    expect($fieldValuesContainValued($modelMock))
        ->toBe(false);
});

it('returns true when attribute in results with fieldValuesContainValue', function (): void {
    $fieldName = fake()->word();
    $valueToFind = fake()->word();

    $modelMock = Mockery::mock(Model::class);
    $modelMock->shouldReceive('getAttribute')
        ->with($fieldName)
        ->andReturn([fake()->word(), fake()->word(), $valueToFind]);

    $fieldValuesContainValued = InfolistHelper::fieldValuesContainValue($fieldName, $valueToFind);

    expect($fieldValuesContainValued($modelMock))
        ->toBe(true);
});

it('returns true when all field values egual with fieldValueEquals', function (bool $value1, bool $value2, bool $expectedResult): void {
    $modelMock = Mockery::mock(Model::class);
    $modelMock->shouldReceive('getAttribute')
        ->with('attribute1')
        ->andReturn($value1);
    $modelMock->shouldReceive('getAttribute')
        ->with('attribute2')
        ->andReturn($value2);

    $fieldValueEquals = InfolistHelper::fieldValueEquals([
        'attribute1' => true,
        'attribute2' => true,
    ]);

    expect($fieldValueEquals($modelMock))
        ->toBe($expectedResult);
})->with([
    [false, false, false],
    [true, false, false],
    [false, true, false],
    [true, true, true],
]);

it('returns true when field is disabled with isFieldDisabled', function (bool $value, bool $expectedResult): void {
    $modelMock = Mockery::mock(Model::class);
    $modelMock->shouldReceive('getAttribute')
        ->with('attribute')
        ->andReturn($value);

    $fieldValueEquals = InfolistHelper::isFieldDisabled('attribute');

    expect($fieldValueEquals($modelMock))
        ->toBe($expectedResult);
})->with([
    [false, true],
    [true, false],
]);

it('fails when field is not a boolean with isFieldDisabled', function (): void {
    $modelMock = Mockery::mock(Model::class);
    $modelMock->shouldReceive('getAttribute')
        ->with('attribute')
        ->andReturn(fake()->word());

    expect(static function () use ($modelMock): void {
        InfolistHelper::isFieldDisabled('attribute')($modelMock);
    })->toThrow(InvalidArgumentException::class, 'method only accepts booleans');
});

it('returns true when field is enabled with isFieldEnabled', function (bool $value, bool $expectedResult): void {
    $modelMock = Mockery::mock(Model::class);
    $modelMock->shouldReceive('getAttribute')
        ->with('attribute')
        ->andReturn($value);

    $fieldValueEquals = InfolistHelper::isFieldEnabled('attribute');

    expect($fieldValueEquals($modelMock))
        ->toBe($expectedResult);
})->with([
    [false, false],
    [true, true],
]);

it('fails when field is not a boolean with isFieldEnabled', function (): void {
    $modelMock = Mockery::mock(Model::class);
    $modelMock->shouldReceive('getAttribute')
        ->with('attribute')
        ->andReturn(fake()->word());

    expect(static function () use ($modelMock): void {
        InfolistHelper::isFieldEnabled('attribute')($modelMock);
    })->toThrow(InvalidArgumentException::class, 'method only accepts booleans');
});

it('returns true when any field is true with isAnyFieldEnabled', function (bool $value1, bool $value2, bool $expectedResult): void {
    $modelMock = Mockery::mock(Model::class);
    $modelMock->shouldReceive('getAttribute')
        ->with('attribute1')
        ->andReturn($value1);
    $modelMock->shouldReceive('getAttribute')
        ->with('attribute2')
        ->andReturn($value2);

    $fieldValueEquals = InfolistHelper::isAnyFieldEnabled([
        'attribute1',
        'attribute2',
    ]);

    expect($fieldValueEquals($modelMock))
        ->toBe($expectedResult);
})->with([
    [false, false, false],
    [true, false, true],
    [false, true, true],
    [true, true, true],
]);
