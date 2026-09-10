<?php

declare(strict_types=1);

namespace App\Filament\Forms\Components;

use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Forms\Components\Field;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Wizard\Step;

use function blank;

class ProcessingRecordStep extends Step
{
    /**
     * Returns whether every required field in this step has been filled.
     *
     * This intentionally does not perform validation, rules are not evaluated.
     */
    public function hasRequiredFieldsFilled(): bool
    {
        return $this->requiredFieldsAreFilled(
            $this->getChildSchema()?->getComponents() ?? [],
        );
    }

    /**
     * @param array<Action|ActionGroup|Component> $components
     */
    private function requiredFieldsAreFilled(array $components): bool
    {
        foreach ($components as $component) {
            if (!$component instanceof Component) {
                continue;
            }

            $container = $component->getChildSchema();
            if ($container !== null && !$this->requiredFieldsAreFilled($container->getComponents())) {
                return false;
            }

            if ($this->isUnfilledRequiredField($component)) {
                return false;
            }
        }

        return true;
    }

    private function isUnfilledRequiredField(Component $component): bool
    {
        if (!$component instanceof Field) {
            return false;
        }

        if ($component->isHidden() || $component->isDisabled()) {
            return false;
        }

        if (!$component->isRequired()) {
            return false;
        }

        return blank($component->getState());
    }
}
