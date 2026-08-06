<?php

declare(strict_types=1);

namespace App\Filament\Forms\Components;

use Filament\Forms\Components\Component;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\Wizard;

use function blank;

class ProcessingRecordStep extends Wizard\Step
{
    /**
     * Returns whether every required field in this step has been filled.
     *
     * This intentionally does not perform validation, rules are not evaluated.
     */
    public function hasRequiredFieldsFilled(): bool
    {
        return $this->requiredFieldsAreFilled(
            $this->getChildComponentContainer()->getComponents(),
        );
    }

    /**
     * @param array<Component> $components
     */
    private function requiredFieldsAreFilled(array $components): bool
    {
        foreach ($components as $component) {
            $container = $component->getChildComponentContainer();
            if (!$this->requiredFieldsAreFilled($container->getComponents())) {
                return false;
            }

            if (!$component instanceof Field) {
                continue;
            }

            if ($component->isHidden() || $component->isDisabled()) {
                continue;
            }

            if (!$component->isRequired()) {
                continue;
            }

            if (blank($component->getState())) {
                return false;
            }
        }

        return true;
    }
}
