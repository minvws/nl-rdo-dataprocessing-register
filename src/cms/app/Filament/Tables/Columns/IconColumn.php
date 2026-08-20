<?php

declare(strict_types=1);

namespace App\Filament\Tables\Columns;

use Closure;
use Filament\Tables\Columns\IconColumn as FilamentIconColumn;
use Webmozart\Assert\Assert;

use function __;
use function boolval;

/**
 * An icon on its own carries no information for assistive software, so every rendered icon is
 * accompanied by a text alternative that is only exposed to screen readers (WCAG 1.1.1).
 */
class IconColumn extends FilamentIconColumn
{
    protected string $view = 'filament.tables.columns.icon_column';

    protected Closure|string|null $textAlternative = null;

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
