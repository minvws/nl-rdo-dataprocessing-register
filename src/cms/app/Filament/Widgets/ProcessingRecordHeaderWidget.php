<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Enums\Authorization\Role;
use App\Facades\Authorization;
use App\Models\FgRemark;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Widgets\Widget;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Livewire\Exceptions\PropertyNotFoundException;
use Webmozart\Assert\Assert;

use function __;

class ProcessingRecordHeaderWidget extends Widget implements HasForms
{
    use InteractsWithForms;

    public Model $record;

    /** @var array<string, mixed>|null $data */
    public ?array $data = [];

    protected int | string | array $columnSpan = 'full';
    protected static string $view = 'filament.widgets.fg-form-widget';

    public static function canView(): bool
    {
        return Authorization::hasRole(Role::DATA_PROTECTION_OFFICIAL);
    }

    /**
     * @throws PropertyNotFoundException
     */
    public function mount(Model $record): void
    {
        $form = $this->getWidgetForm();

        $fgRemark = $record->getAttribute('fgRemark');

        /** @var array<string, mixed>|null $state */
        $state = $fgRemark instanceof FgRemark ? $fgRemark->toArray() : null;

        $form->fill($state);
    }

    public function form(Form $form): Form
    {
        return $form
            ->statePath('data')
            ->schema([
                Textarea::make('body')
                    ->label(__('general.fg_remarks'))
                    ->columnSpanFull()
                    ->autosize(),
            ]);
    }

    /**
     * @throws PropertyNotFoundException
     */
    public function submit(): void
    {
        $this->updateRecord($this->getWidgetForm()->getState());

        Notification::make()
            ->success()
            ->title(__('general.saved'))
            ->send();
    }

    /**
     * @throws PropertyNotFoundException
     */
    private function getWidgetForm(): Form
    {
        $form = $this->__get('form');
        Assert::isInstanceOf($form, Form::class);

        return $form;
    }

    /**
     * @param array<string, mixed> $attributes
     */
    private function updateRecord(array $attributes): void
    {
        $model = $this->record;
        Assert::methodExists($model, 'fgRemark');

        /** @var MorphOne<FgRemark, Model> $fgRemarkRelation */
        $fgRemarkRelation = $model->fgRemark();

        /** @var FgRemark $fgRemark */
        $fgRemark = $fgRemarkRelation->firstOrCreate();
        $fgRemark->fill($attributes);

        $fgRemarkRelation->save($fgRemark);
    }
}
