<?php

declare(strict_types=1);

namespace App\Filament\Forms;

use App\Facades\Authentication;
use Closure;
use Filament\Forms\Get;
use Illuminate\Support\Collection;

use function in_array;
use function is_array;
use function is_bool;

class FormHelper
{
    public static function addAuthFields(): Closure
    {
        return static function (array $data) {
            $data['organisation_id'] = Authentication::organisation()->id;
            $data['user_id'] = Authentication::user()->id;

            return $data;
        };
    }

    public static function fieldValuesContainValue(
        string $optionsSelectionId,
        string $valueToTriggerAdditionalDescriptionField,
    ): Closure {
        return static function (Get $get) use ($optionsSelectionId, $valueToTriggerAdditionalDescriptionField): bool {
            $selectedOptions = $get($optionsSelectionId);

            if (!is_array($selectedOptions)) {
                // cannot throw an exception here, because forms are somehow loaded in relation managers
                return false;
            }

            return in_array($valueToTriggerAdditionalDescriptionField, $selectedOptions, true);
        };
    }

    /**
     * @param array<string, bool|string> $fieldValues
     */
    public static function fieldValueEquals(array $fieldValues): Closure
    {
        return static function (Get $get) use ($fieldValues): bool {
            foreach ($fieldValues as $field => $value) {
                $result = $get($field);
                if ($result !== $value) {
                    return false;
                }
            }

            return true;
        };
    }

    public static function isFieldDisabled(string $name): Closure
    {
        return static function (Get $get) use ($name): bool {
            $value = $get($name);

            if (!is_bool($value)) {
                // cannot throw an exception here, because forms are somehow loaded in relation managers
                return false;
            }

            return !$value;
        };
    }

    public static function isFieldEnabled(string $name): Closure
    {
        return static function (Get $get) use ($name): bool {
            $value = $get($name);

            if (!is_bool($value)) {
                // cannot throw an exception here, because forms are somehow loaded in relation managers
                return false;
            }

            return $value;
        };
    }

    /**
     * @param array<string> $fields
     */
    public static function isAnyFieldEnabled(array $fields): Closure
    {
        return static function (Get $get) use ($fields): bool {
            foreach ($fields as $field) {
                if (self::isFieldEnabled($field)($get)) {
                    return true;
                }
            }

            return false;
        };
    }

    /**
     * @param array<string> $values
     *
     * @return array<string, string>
     */
    public static function setValueAsKey(array $values): array
    {
        $optionsCollection = new Collection($values);

        $optionsCollection = $optionsCollection->keyBy(static function (string $value): string {
            return $value;
        });

        /** @var array<string, string> $options */
        $options = $optionsCollection->toArray();

        return $options;
    }
}
