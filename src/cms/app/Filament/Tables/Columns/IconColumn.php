<?php

declare(strict_types=1);

namespace App\Filament\Tables\Columns;

use Closure;
use Filament\Tables\Columns\IconColumn as FilamentIconColumn;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Webmozart\Assert\Assert;

use function __;
use function array_filter;
use function array_map;
use function boolval;
use function e;
use function implode;

/**
 * An icon on its own carries no information for assistive software, so every rendered icon is
 * accompanied by a text alternative that is only exposed to screen readers (WCAG 1.1.1).
 */
class IconColumn extends FilamentIconColumn
{
    protected Closure|string|null $textAlternative = null;

    public function toEmbeddedHtml(): string
    {
        $states = $this->getState();
        $states = $states instanceof Collection ? $states->all() : $states;

        $textAlternatives = array_filter(
            array_map(
                function (mixed $state): ?string {
                    return $this->getTextAlternative($state);
                },
                Arr::wrap($states),
            ),
            static function (?string $textAlternative): bool {
                return $textAlternative !== null && $textAlternative !== '';
            },
        );

        return '<span class="sr-only">' . e(implode(', ', $textAlternatives)) . '</span>' . parent::toEmbeddedHtml();
    }

    public function textAlternative(Closure|string|null $textAlternative): static
    {
        $this->textAlternative = $textAlternative;

        return $this;
    }

    public function getTextAlternative(mixed $state): ?string
    {
        if ($this->textAlternative !== null) {
            /** @var mixed $textAlternative evaluate() is generic over its argument, which here includes Closure */
            $textAlternative = $this->evaluate($this->textAlternative, ['state' => $state]);
            Assert::nullOrString($textAlternative);

            return $textAlternative;
        }

        if ($this->isBoolean()) {
            return boolval($state) ? __('general.yes') : __('general.no');
        }

        return null;
    }
}
