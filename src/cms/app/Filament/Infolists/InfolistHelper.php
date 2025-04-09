<?php

declare(strict_types=1);

namespace App\Filament\Infolists;

use Closure;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;
use Webmozart\Assert\Assert;

use function in_array;
use function is_bool;

class InfolistHelper
{
    public static function fieldValuesContainValue(
        string $modelAttribute,
        string $valueToFind,
    ): Closure {
        return static function (Model $record) use ($modelAttribute, $valueToFind): bool {
            $attributeValues = $record->getAttribute($modelAttribute);
            if ($attributeValues === null) {
                return false;
            }

            Assert::isArray($attributeValues);

            return in_array($valueToFind, $attributeValues, true);
        };
    }

    /**
     * @param array<string, bool|string> $fieldValues
     */
    public static function fieldValueEquals(array $fieldValues): Closure
    {
        return static function (Model $record) use ($fieldValues): bool {
            foreach ($fieldValues as $field => $value) {
                $result = $record->getAttribute($field);
                if ($result !== $value) {
                    return false;
                }
            }

            return true;
        };
    }

    public static function isFieldDisabled(string $name): Closure
    {
        return static function (Model $record) use ($name): bool {
            $value = $record->getAttribute($name);

            if (!is_bool($value)) {
                throw new InvalidArgumentException('method only accepts booleans');
            }

            return !$value;
        };
    }

    public static function isFieldEnabled(string $name): Closure
    {
        return static function (Model $record) use ($name): bool {
            $value = $record->getAttribute($name);

            if (!is_bool($value)) {
                throw new InvalidArgumentException('method only accepts booleans');
            }

            return $value;
        };
    }

    /**
     * @param array<string> $fields
     */
    public static function isAnyFieldEnabled(array $fields): Closure
    {
        return static function (Model $record) use ($fields): bool {
            foreach ($fields as $field) {
                if (self::isFieldEnabled($field)($record)) {
                    return true;
                }
            }

            return false;
        };
    }
}
