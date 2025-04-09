<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord as FilamentCreateRecord;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Model;
use Webmozart\Assert\Assert;

use function __;

abstract class CreateRecord extends FilamentCreateRecord
{
    /**
     * Override to always redirect to edit page
     */
    protected function getRedirectUrl(): string
    {
        /** @var class-string<Resource> $resource */
        $resource = static::getResource();

        $record = $this->getRecord();
        Assert::isInstanceOf($record, Model::class);

        return $resource::getUrl('edit', ['record' => $record, ...$this->getRedirectUrlParameters()]);
    }

    protected function getCreateFormAction(): Action
    {
        return parent::getCreateFormAction()
            ->label(__('general.create_form_action_label'));
    }

    protected function getCreateAnotherFormAction(): Action
    {
        return parent::getCreateAnotherFormAction()
            ->label(__('general.create_another_form_action_label'));
    }
}
