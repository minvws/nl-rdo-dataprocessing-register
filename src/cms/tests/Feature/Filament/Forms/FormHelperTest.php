<?php

declare(strict_types=1);

namespace Tests\Feature\Filament\Resources\AvgProcessorProcessingRecordResource;

use App\Filament\Forms\FormHelper;
use Filament\Forms\Get;
use Mockery;

use function expect;
use function fake;
use function it;

it('returns get correct state on fieldValuesContainValue', function (array $fieldValues, string $value, bool $expectedResult): void {
    $fieldName = fake()->word();

    $getterMock = Mockery::mock(Get::class);
    $getterMock->shouldReceive('__invoke')
        ->with($fieldName)
        ->andReturn($fieldValues);

    $isFieldEnabled = FormHelper::fieldValuesContainValue($fieldName, $value);

    expect($isFieldEnabled($getterMock))
        ->toBe($expectedResult);
})->with([
    [['foo', 'bar'], 'foo', true],
    [['foo', 'bar'], 'bar', true],
    [['foo', 'bar'], 'baz`', false],
]);

it('returns false if a non-array value is passed to get the fieldValuesContainValue', function (): void {
    $fieldName = fake()->word();

    $getterMock = Mockery::mock(Get::class);
    $getterMock->shouldReceive('__invoke')
        ->with($fieldName)
        ->andReturn(false);

    $isFieldEnabled = FormHelper::fieldValuesContainValue($fieldName, fake()->word());

    expect($isFieldEnabled($getterMock))
        ->toBeFalse();
});

it('returns get correct enabled-status of a field', function (bool $status, bool $expectedResult): void {
    $fieldName = fake()->word();

    $getterMock = Mockery::mock(Get::class);
    $getterMock->shouldReceive('__invoke')
        ->with($fieldName)
        ->andReturn($status);

    $isFieldEnabled = FormHelper::isFieldEnabled($fieldName);

    expect($isFieldEnabled($getterMock))
        ->toBe($expectedResult);
})->with([
    [true, true],
    [false, false],
]);

it('returns get correct disabled-status of a field', function (bool $status, bool $expectedResult): void {
    $fieldName = fake()->word();

    $getterMock = Mockery::mock(Get::class);
    $getterMock->shouldReceive('__invoke')
        ->with($fieldName)
        ->andReturn($status);

    $isFieldEnabled = FormHelper::isFieldDisabled($fieldName);

    expect($isFieldEnabled($getterMock))
        ->toBe($expectedResult);
})->with([
    [true, false],
    [false, true],
]);

it('returns false if a non-boolean value is passed to get the enabled-status', function (): void {
    $fieldName = fake()->word();

    $getterMock = Mockery::mock(Get::class);
    $getterMock->shouldReceive('__invoke')
        ->with($fieldName)
        ->andReturn([]);

    $isFieldEnabled = FormHelper::isFieldEnabled($fieldName);

    expect($isFieldEnabled($getterMock))
        ->toBeFalse();
});

it('returns false if a non-boolean value is passed to get the disabled-status', function (): void {
    $fieldName = fake()->word();

    $getterMock = Mockery::mock(Get::class);
    $getterMock->shouldReceive('__invoke')
        ->with($fieldName)
        ->andReturn([]);

    $isFieldEnabled = FormHelper::isFieldDisabled($fieldName);

    expect($isFieldEnabled($getterMock))
        ->toBeFalse();
});

it('returns the correct auth-fields', function (): void {
    $addAuthFields = FormHelper::addAuthFields();
    $authFields = $addAuthFields([]);

    expect($authFields['organisation_id'])
        ->toBe($this->organisation->id)
        ->and($authFields['user_id'])
        ->toBe($this->user->id);
});

it('returns correct value if any field is enabled', function (bool $fieldA, bool $fieldB, bool $expectedResult): void {
    $getterMock = Mockery::mock(Get::class);
    $getterMock->shouldReceive('__invoke')
        ->with('a')
        ->andReturn($fieldA);
    $getterMock->shouldReceive('__invoke')
        ->with('b')
        ->andReturn($fieldB);

    $isAnyFieldEnabled = FormHelper::isAnyFieldEnabled(['a', 'b']);

    expect($isAnyFieldEnabled($getterMock))
        ->toBe($expectedResult);
})->with([
    [true, true, true],
    [true, false, true],
    [false, true, true],
    [false, false, false],
]);
