<?php

declare(strict_types=1);

namespace App\Filament\Resources\AvgProcessorProcessingRecordResource;

use App\Filament\Forms\Components\OutsideEuCountryInputGroup;
use App\Filament\Forms\Components\PeriodicReviewField;
use App\Filament\Forms\Components\ProcessingRecordWizard;
use App\Filament\Forms\Components\Radio\CoreEntityDataCollectionSource;
use App\Filament\Forms\Components\RemarksField;
use App\Filament\Forms\Components\Repeater\AvgGoalsRepeater;
use App\Filament\Forms\Components\Section\InformationBlockSection;
use App\Filament\Forms\Components\Select\ParentSelect;
use App\Filament\Forms\Components\Select\SelectSingleWithLookup;
use App\Filament\Forms\Components\SelectMultipleWithLookup;
use App\Filament\Forms\Components\StakeholdersRepeater;
use App\Filament\Forms\Components\TextInput\EntityNumber;
use App\Filament\Forms\Components\TextInput\ImportNumber;
use App\Filament\Forms\FormHelper;
use App\Filament\Resources\DocumentResource\DocumentResourceForm;
use App\Filament\Resources\LookupListResource\LookupListResourceForm;
use App\Filament\Resources\ProcessorResource\ProcessorResourceForm;
use App\Filament\Resources\ReceiverResource\ReceiverResourceForm;
use App\Filament\Resources\ResponsibleResource\ResponsibleResourceForm;
use App\Filament\Resources\SystemResource\SystemResourceForm;
use App\Models\Avg\AvgProcessorProcessingRecordService;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Form;

use function __;

class AvgProcessorProcessingRecordResourceForm
{
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                ProcessingRecordWizard::make()
                    ->schema([
                        self::getStepName(),
                        self::getStepResponsible(),
                        self::getStepProcessors(),
                        self::getStepReceiver(),
                        self::getStepProcessingGoal(),
                        self::getStepInvolvedData(),
                        self::getStepDecisionMaking(),
                        self::getStepSystem(),
                        self::getStepSecurity(),
                        self::getStepPassthrough(),
                        self::getStepGebPia(),
                        self::getStepContactPerson(),
                        self::getStepAttachments(),
                        self::getStepRemarks(),
                    ])
                    ->skippable()
                    ->persistStepInQueryString()
                    ->columnSpanFull(),
            ]);
    }

    private static function getStepName(): Step
    {
        return Step::make(__('avg_processor_processing_record.step_processing_name'))
            ->schema([
                Grid::make()
                    ->schema([
                        EntityNumber::make(),
                        ImportNumber::make(),
                    ]),
                TextInput::make('name')
                    ->label(__('processing_record.name'))
                    ->required()
                    ->maxLength(255),
                CoreEntityDataCollectionSource::make(),
                SelectSingleWithLookup::makeWithDisabledOptions(
                    'avg_processor_processing_record_service_id',
                    'avgProcessorProcessingRecordService',
                    AvgProcessorProcessingRecordService::class,
                    'name',
                )
                    ->label(__('avg_processor_processing_record_service.model_singular')),
                PeriodicReviewField::make(),
                ParentSelect::make()
                    ->hintIcon('heroicon-o-information-circle', __('general.parent_hint_icon_text')),
                InformationBlockSection::makeCollapsible(
                    __('information_blocks.avg_processor_processing_record.step_processing_name_title'),
                    __('information_blocks.avg_processor_processing_record.step_processing_name_info'),
                    __('information_blocks.avg_processor_processing_record.step_processing_name_extra_info'),
                ),
            ]);
    }

    private static function getStepResponsible(): Step
    {
        return Step::make(__('avg_processor_processing_record.step_responsible'))
            ->schema([
                SelectMultipleWithLookup::makeForRelationshipWithCreate(
                    'responsible_id',
                    'responsibles',
                    ResponsibleResourceForm::getSchema(),
                    'name',
                )
                    ->label(__('responsible.model_plural'))
                    ->required(),
                InformationBlockSection::makeCollapsible(
                    __('information_blocks.avg_processor_processing_record.step_responsible_title'),
                    __('information_blocks.avg_processor_processing_record.step_responsible_info'),
                    __('information_blocks.avg_processor_processing_record.step_responsible_extra_info'),
                ),
            ]);
    }

    private static function getStepProcessors(): Step
    {
        return Step::make(__('avg_processor_processing_record.step_processor'))
            ->schema([
                Toggle::make('has_processors')
                    ->label(__('avg_processor_processing_record.has_subprocessors'))
                    ->default(false)
                    ->live(),

                SelectMultipleWithLookup::makeForRelationshipWithCreate(
                    'processors',
                    'processors',
                    ProcessorResourceForm::getSchema(),
                    'name',
                )
                    ->label(__('avg_processor_processing_record.subprocessors'))
                    ->visible(FormHelper::isFieldEnabled('has_processors')),
                InformationBlockSection::makeCollapsible(
                    __('information_blocks.avg_processor_processing_record.step_processor_title'),
                    __('information_blocks.avg_processor_processing_record.step_processor_info'),
                    __('information_blocks.avg_processor_processing_record.step_processor_extra_info'),
                ),
            ]);
    }

    private static function getStepReceiver(): Step
    {
        return Step::make(__('avg_processor_processing_record.step_receiver'))->schema([
            SelectMultipleWithLookup::makeForRelationshipWithCreate(
                'receivers',
                'receivers',
                ReceiverResourceForm::getSchema(),
                'description',
            )
                ->label(__('receiver.model_plural')),
            InformationBlockSection::makeCollapsible(
                __('information_blocks.avg_processor_processing_record.step_receiver_title'),
                __('information_blocks.avg_processor_processing_record.step_receiver_info'),
                __('information_blocks.avg_processor_processing_record.step_receiver_extra_info'),
            ),
        ]);
    }

    private static function getStepProcessingGoal(): Step
    {
        return Step::make(__('avg_processor_processing_record.step_processing_goal'))
            ->schema([
                Toggle::make('has_goal')
                    ->label(__('avg_processor_processing_record.has_goal'))
                    ->default(false)
                    ->live(),
                AvgGoalsRepeater::make()
                    ->visible(FormHelper::isFieldEnabled('has_goal')),
                InformationBlockSection::makeCollapsible(
                    __('information_blocks.avg_processor_processing_record.step_processing_goal_title'),
                    __('information_blocks.avg_processor_processing_record.step_processing_goal_info'),
                    __('information_blocks.avg_processor_processing_record.step_processing_goal_extra_info'),
                ),
            ]);
    }

    private static function getStepInvolvedData(): Step
    {
        return Step::make(__('avg_processor_processing_record.step_involved_data'))
            ->schema([
                Toggle::make('has_involved')
                    ->label(__('avg_processor_processing_record.has_involved'))
                    ->default(false)
                    ->live()
                    ->helperText(__('avg_processor_processing_record.help_has_involved')),

                StakeholdersRepeater::make()
                    ->visible(FormHelper::isFieldEnabled('has_involved')),
                InformationBlockSection::makeCollapsible(
                    __('information_blocks.avg_processor_processing_record.step_stakeholder_data_title'),
                    __('information_blocks.avg_processor_processing_record.step_stakeholder_data_info'),
                    __('information_blocks.avg_processor_processing_record.step_stakeholder_data_extra_info'),
                ),
            ]);
    }

    private static function getStepDecisionMaking(): Step
    {
        return Step::make(__('avg_processor_processing_record.step_decision_making'))
            ->schema([
                Toggle::make('decision_making')
                    ->label(__('avg_processor_processing_record.decision_making'))
                    ->default(false)
                    ->live()
                    ->helperText(__('avg_processor_processing_record.help_decision_making')),

                Group::make()
                    ->visible(FormHelper::isFieldEnabled('decision_making'))
                    ->schema([
                        Textarea::make('logic')
                            ->required(FormHelper::isFieldEnabled('decision_making'))
                            ->label(__('avg_processor_processing_record.logic')),

                        Textarea::make('importance_consequences')
                            ->required(FormHelper::isFieldEnabled('decision_making'))
                            ->label(__('avg_processor_processing_record.importance_consequences')),
                    ]),
                InformationBlockSection::makeCollapsible(
                    __('information_blocks.avg_processor_processing_record.step_decision_making_title'),
                    __('information_blocks.avg_processor_processing_record.step_decision_making_info'),
                    __('information_blocks.avg_processor_processing_record.step_decision_making_extra_info'),
                ),
            ]);
    }

    private static function getStepSystem(): Step
    {
        return Step::make(__('avg_processor_processing_record.step_system'))->schema([
            Toggle::make('has_systems')
                ->helperText(__('avg_processor_processing_record.help_has_systems'))
                ->label(__('avg_processor_processing_record.has_systems'))
                ->default(false)
                ->live(),

            SelectMultipleWithLookup::makeForRelationshipWithCreate('systems', 'systems', SystemResourceForm::getSchema(), 'description')
                ->label(__('system.model_plural'))
                ->required()
                ->visible(FormHelper::isFieldEnabled('has_systems')),
            InformationBlockSection::makeCollapsible(
                __('information_blocks.avg_processor_processing_record.step_system_title'),
                __('information_blocks.avg_processor_processing_record.step_system_info'),
                __('information_blocks.avg_processor_processing_record.step_system_extra_info'),
            ),
        ]);
    }

    private static function getStepSecurity(): Step
    {
        return Step::make(__('avg_processor_processing_record.step_security'))
            ->schema([
                Toggle::make('has_security')
                    ->helperText(__('avg_processor_processing_record.help_has_security'))
                    ->label(__('avg_processor_processing_record.has_security'))
                    ->default(false)
                    ->live(),

                Group::make()
                    ->visible(FormHelper::isFieldEnabled('has_security'))
                    ->schema([
                        Section::make(__('processor.measures'))
                            ->schema([
                                Checkbox::make('measures_implemented')
                                    ->label(__('processor.measures_implemented')),
                                Checkbox::make('other_measures')
                                    ->label(__('processor.other_measures')),
                                Textarea::make('measures_description')
                                    ->label(__('processor.measures_description')),
                            ]),

                        Section::make(__('avg_processor_processing_record.section_pseudonymization'))
                            ->schema([
                                Toggle::make('has_pseudonymization')
                                    ->label(__('avg_processor_processing_record.has_pseudonymization'))
                                    ->default(false)
                                    ->live(),
                                Textarea::make('pseudonymization')
                                    ->label(__('avg_processor_processing_record.pseudonymization'))
                                    ->default('')
                                    ->required(FormHelper::isFieldEnabled('has_pseudonymization'))
                                    ->visible(FormHelper::isFieldEnabled('has_pseudonymization')),
                            ]),
                    ]),
                InformationBlockSection::makeCollapsible(
                    __('information_blocks.avg_processor_processing_record.step_security_title'),
                    __('information_blocks.avg_processor_processing_record.step_security_info'),
                    __('information_blocks.avg_processor_processing_record.step_security_extra_info'),
                ),
            ]);
    }

    private static function getStepPassthrough(): Step
    {
        return Step::make(__('avg_processor_processing_record.step_passthrough'))
            ->schema([
                Toggle::make('outside_eu')
                    ->helperText(__('avg_processor_processing_record.help_outside_eu'))
                    ->label(__('avg_processor_processing_record.outside_eu'))
                    ->default(false)
                    ->live(),

                Group::make()
                    ->visible(FormHelper::isFieldEnabled('outside_eu'))
                    ->schema([
                        OutsideEuCountryInputGroup::make(),

                        Toggle::make('outside_eu_protection_level')
                            ->label(__('avg_processor_processing_record.outside_eu_protection_level'))
                            ->live(),

                        Textarea::make('outside_eu_protection_level_description')
                            ->label(__('avg_processor_processing_record.outside_eu_protection_level_description'))
                            ->helperText(__('avg_processor_processing_record.help_outside_eu_protection_level_description'))
                            ->required(FormHelper::isFieldDisabled('outside_eu_protection_level'))
                            ->visible(FormHelper::isFieldDisabled('outside_eu_protection_level')),

                        Textarea::make('outside_eu_description')
                            ->label(__('avg_processor_processing_record.outside_eu_description')),
                    ]),
                InformationBlockSection::makeCollapsible(
                    __('information_blocks.avg_processor_processing_record.step_passthrough_title'),
                    __('information_blocks.avg_processor_processing_record.step_passthrough_info'),
                    __('information_blocks.avg_processor_processing_record.step_passthrough_extra_info'),
                ),
            ]);
    }

    private static function getStepGebPia(): Step
    {
        return Step::make(__('avg_processor_processing_record.step_geb_pia'))
            ->schema([
                Toggle::make('geb_pia')
                    ->label(__('avg_processor_processing_record.geb_pia'))
                    ->helperText(__('avg_processor_processing_record.help_geb_pia')),
                InformationBlockSection::makeCollapsible(
                    __('information_blocks.avg_processor_processing_record.step_geb_dpia_title'),
                    __('information_blocks.avg_processor_processing_record.step_geb_dpia_info'),
                    __('information_blocks.avg_processor_processing_record.step_geb_dpia_extra_info'),
                ),
            ]);
    }

    private static function getStepContactPerson(): Step
    {
        return Step::make(__('avg_processor_processing_record.step_contact_person'))
            ->schema([
                SelectMultipleWithLookup::makeForRelationshipWithCreate(
                    'contactPersons',
                    'contactPersons',
                    LookupListResourceForm::getSchema(),
                    'name',
                )
                    ->label(__('contact_person.model_plural')),
                InformationBlockSection::makeCollapsible(
                    __('information_blocks.avg_processor_processing_record.step_contact_person_title'),
                    __('information_blocks.avg_processor_processing_record.step_contact_person_info'),
                ),
            ]);
    }

    private static function getStepAttachments(): Step
    {
        return Step::make(__('avg_processor_processing_record.step_attachments'))
            ->schema([
                SelectMultipleWithLookup::makeForRelationshipWithCreate(
                    'document_id',
                    'documents',
                    DocumentResourceForm::getSchema(),
                    'name',
                )
                    ->label(__('document.model_plural')),
                InformationBlockSection::makeCollapsible(
                    __('information_blocks.avg_processor_processing_record.step_attachments_title'),
                    __('information_blocks.avg_processor_processing_record.step_attachments_info'),
                ),
            ]);
    }

    private static function getStepRemarks(): Step
    {
        return Step::make(__('avg_processor_processing_record.step_remarks'))
            ->schema([
                RemarksField::make()
                    ->mutateRelationshipDataBeforeCreateUsing(FormHelper::addAuthFields())
                    ->mutateRelationshipDataBeforeSaveUsing(FormHelper::addAuthFields()),
                InformationBlockSection::makeCollapsible(
                    __('information_blocks.avg_processor_processing_record.step_remarks_title'),
                    __('information_blocks.avg_processor_processing_record.step_remarks_info'),
                    __('information_blocks.avg_processor_processing_record.step_remarks_extra_info'),
                ),
            ]);
    }
}
