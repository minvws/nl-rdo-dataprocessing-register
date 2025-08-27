<?php

declare(strict_types=1);

namespace App\Filament\Resources\AvgResponsibleProcessingRecordResource;

use App\Filament\Infolists\Components\AvgGoalsRepeatableEntry;
use App\Filament\Infolists\Components\CheckboxEntry;
use App\Filament\Infolists\Components\DateEntry;
use App\Filament\Infolists\Components\DateTimeEntry;
use App\Filament\Infolists\Components\EntityNumberEntry;
use App\Filament\Infolists\Components\ImportNumberEntry;
use App\Filament\Infolists\Components\ParentSelectEntry;
use App\Filament\Infolists\Components\RemarksEntry;
use App\Filament\Infolists\Components\Section\InformationBlockSection;
use App\Filament\Infolists\Components\Section\PublicWebsiteCheckSection;
use App\Filament\Infolists\Components\SelectMultipleEntry;
use App\Filament\Infolists\Components\StakeholdersRepeatableEntry;
use App\Filament\Infolists\Components\TextareaEntry;
use App\Filament\Infolists\Components\ToggleEntry;
use App\Filament\Infolists\Group\ProcessingRecordContactPersons;
use App\Filament\Infolists\InfolistHelper;
use Filament\Infolists\Components\Component;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;

use function __;

class AvgResponsibleProcessingRecordResourceInfolistSchemas
{
    /**
     * @return array<Component>
     */
    public static function getProcessingName(): array
    {
        return [
            Grid::make()
                ->schema([
                    EntityNumberEntry::make(),
                    ImportNumberEntry::make(),
                ]),
            TextEntry::make('name')
                ->label(__('processing_record.name')),
            TextEntry::make('data_collection_source')
                ->label(__('general.data_collection_source')),
            TextEntry::make('avgResponsibleProcessingRecordService.name')
                ->label(__('avg_responsible_processing_record_service.model_singular')),
            SelectMultipleEntry::make('tags.name')
                ->label(__('tag.model_plural')),
            DateEntry::make('review_at')
                ->label(__('general.review_at')),
            ParentSelectEntry::make(),
            InformationBlockSection::makeCollapsible(
                __('information_blocks.avg_responsible_processing_record.step_processing_name_title'),
                __('information_blocks.avg_responsible_processing_record.step_processing_name_info'),
                __('information_blocks.avg_responsible_processing_record.step_processing_name_extra_info'),
            ),
        ];
    }

    /**
     * @return array<Component>
     */
    public static function getResponsible(): array
    {
        return [
            SelectMultipleEntry::make('responsibles.name')
                ->label(__('responsible.model_plural')),
            TextareaEntry::make('responsibility_distribution')
                ->label(__('avg_responsible_processing_record.responsibility_distribution')),
            InformationBlockSection::makeCollapsible(
                __('information_blocks.avg_responsible_processing_record.step_responsible_title'),
                __('information_blocks.avg_responsible_processing_record.step_responsible_info'),
                __('information_blocks.avg_responsible_processing_record.step_responsible_extra_info'),
            ),
        ];
    }

    /**
     * @return array<Component>
     */
    public static function getProcessor(): array
    {
        return [
            ToggleEntry::make('has_processors')
                ->label(__('avg_responsible_processing_record.has_processors')),
            SelectMultipleEntry::make('processors.name')
                ->label(__('processor.model_plural'))
                ->visible(InfolistHelper::isFieldEnabled('has_processors')),
            InformationBlockSection::makeCollapsible(
                __('information_blocks.avg_responsible_processing_record.step_processor_title'),
                __('information_blocks.avg_responsible_processing_record.step_processor_info'),
            ),
        ];
    }

    /**
     * @return array<Component>
     */
    public static function getReceiver(): array
    {
        return [
            SelectMultipleEntry::make('receivers.description')
                ->label(__('receiver.model_plural')),
            InformationBlockSection::makeCollapsible(
                __('information_blocks.avg_responsible_processing_record.step_receiver_title'),
                __('information_blocks.avg_responsible_processing_record.step_receiver_info'),
                __('information_blocks.avg_responsible_processing_record.step_receiver_extra_info'),
            ),
        ];
    }

    /**
     * @return array<Component>
     */
    public static function getProcessingGoal(): array
    {
        return [
            AvgGoalsRepeatableEntry::make(),
            InformationBlockSection::makeCollapsible(
                __('information_blocks.avg_responsible_processing_record.step_processing_goal_title'),
                __('information_blocks.avg_responsible_processing_record.step_processing_goal_info'),
                __('information_blocks.avg_responsible_processing_record.step_processing_goal_extra_info'),
            ),
        ];
    }

    /**
     * @return array<Component>
     */
    public static function getStakeholder(): array
    {
        return [
            StakeholdersRepeatableEntry::make(),
            InformationBlockSection::makeCollapsible(
                __('information_blocks.avg_responsible_processing_record.step_stakeholder_data_title'),
                __('information_blocks.avg_responsible_processing_record.step_stakeholder_data_info'),
                __('information_blocks.avg_responsible_processing_record.step_stakeholder_data_extra_info'),
            ),
        ];
    }

    /**
     * @return array<Component>
     */
    public static function getDecisionMaking(): array
    {
        return [
            ToggleEntry::make('decision_making')
                ->label(__('avg_responsible_processing_record.decision_making')),
            Group::make()
                ->visible(InfolistHelper::isFieldEnabled('decision_making'))
                ->schema([
                    TextareaEntry::make('logic')
                        ->label(__('avg_responsible_processing_record.logic')),
                    TextareaEntry::make('importance_consequences')
                        ->label(__('avg_responsible_processing_record.importance_consequences')),
                ]),
            InformationBlockSection::makeCollapsible(
                __('information_blocks.avg_responsible_processing_record.step_decision_making_title'),
                __('information_blocks.avg_responsible_processing_record.step_decision_making_info'),
                __('information_blocks.avg_responsible_processing_record.step_decision_making_extra_info'),
            ),
        ];
    }

    /**
     * @return array<Component>
     */
    public static function getSystem(): array
    {
        return [
            ToggleEntry::make('has_systems')
                ->label(__('avg_responsible_processing_record.has_systems')),
            SelectMultipleEntry::make('systems.description')
                ->label(__('avg_responsible_processing_record.logic')),
            InformationBlockSection::makeCollapsible(
                __('information_blocks.avg_responsible_processing_record.step_system_title'),
                __('information_blocks.avg_responsible_processing_record.step_system_info'),
                __('information_blocks.avg_responsible_processing_record.step_system_extra_info'),
            ),
        ];
    }

    /**
     * @return array<Component>
     */
    public static function getSecurity(): array
    {
        return [
            ToggleEntry::make('has_security')
                ->label(__('avg_responsible_processing_record.has_security')),

            Group::make()
                ->visible(InfolistHelper::isFieldEnabled('has_security'))
                ->schema([
                    Section::make(__('processor.measures'))
                        ->schema([
                            CheckboxEntry::make('measures_implemented')
                                ->label(__('processor.measures_implemented')),
                            CheckboxEntry::make('other_measures')
                                ->label(__('processor.other_measures')),
                            TextareaEntry::make('measures_description')
                                ->label(__('processor.measures_description')),
                        ]),

                    Section::make(__('processor.measures'))
                        ->schema([
                            ToggleEntry::make('has_pseudonymization')
                                ->label(__('avg_responsible_processing_record.has_pseudonymization')),
                            TextareaEntry::make('pseudonymization')
                                ->label(__('avg_responsible_processing_record.pseudonymization'))
                                ->visible(InfolistHelper::isFieldEnabled('has_pseudonymization')),
                        ]),
                ]),
            InformationBlockSection::makeCollapsible(
                __('information_blocks.avg_responsible_processing_record.step_security_title'),
                __('information_blocks.avg_responsible_processing_record.step_security_info'),
                __('information_blocks.avg_responsible_processing_record.step_security_extra_info'),
            ),
        ];
    }

    /**
     * @return array<Component>
     */
    public static function getPassthrough(): array
    {
        return [
            ToggleEntry::make('outside_eu')
                ->label(__('avg_responsible_processing_record.outside_eu')),

            Group::make()
                ->visible(InfolistHelper::isFieldEnabled('outside_eu'))
                ->schema([
                    TextEntry::make('country')
                        ->label(__('general.country')),
                    TextEntry::make('country_other')
                        ->label(__('general.country_other')),

                    ToggleEntry::make('outside_eu_protection_level')
                        ->label(__('avg_responsible_processing_record.outside_eu_protection_level')),
                    TextareaEntry::make('outside_eu_protection_level_description')
                        ->label(__('processor.outside_eu_protection_level_description'))
                        ->visible(InfolistHelper::isFieldEnabled('outside_eu_protection_level')),
                    TextareaEntry::make('outside_eu_description')
                        ->label(__('avg_responsible_processing_record.outside_eu_description')),
                ]),
            InformationBlockSection::makeCollapsible(
                __('information_blocks.avg_responsible_processing_record.step_passthrough_title'),
                __('information_blocks.avg_responsible_processing_record.step_passthrough_info'),
                __('information_blocks.avg_responsible_processing_record.step_passthrough_extra_info'),
            ),
        ];
    }

    /**
     * @return array<Component>
     */
    public static function getGebDpia(): array
    {
        return [
            ToggleEntry::make('geb_dpia_executed')
                ->label(__('avg_responsible_processing_record.geb_dpia_executed')),
            ToggleEntry::make('geb_dpia_automated')
                ->label(__('avg_responsible_processing_record.geb_dpia_automated'))
                ->visible(InfolistHelper::isFieldDisabled('geb_dpia_executed')),
            ToggleEntry::make('geb_dpia_large_scale_processing')
                ->label(__('avg_responsible_processing_record.geb_dpia_large_scale_processing'))
                ->visible(InfolistHelper::fieldValueEquals([
                    'geb_dpia_executed' => false,
                    'geb_dpia_automated' => false,
                ])),
            ToggleEntry::make('geb_dpia_large_scale_monitoring')
                ->label(__('avg_responsible_processing_record.geb_dpia_large_scale_monitoring'))
                ->visible(InfolistHelper::fieldValueEquals([
                    'geb_dpia_executed' => false,
                    'geb_dpia_automated' => false,
                    'geb_dpia_large_scale_processing' => false,
                ])),
            ToggleEntry::make('geb_dpia_list_required')
                ->label(__('avg_responsible_processing_record.geb_dpia_list_required'))
                ->visible(InfolistHelper::fieldValueEquals([
                    'geb_dpia_executed' => false,
                    'geb_dpia_automated' => false,
                    'geb_dpia_large_scale_processing' => false,
                    'geb_dpia_large_scale_monitoring' => false,
                ])),
            ToggleEntry::make('geb_dpia_criteria_wp248')
                ->label(__('avg_responsible_processing_record.geb_dpia_criteria_wp248'))
                ->visible(InfolistHelper::fieldValueEquals([
                    'geb_dpia_executed' => false,
                    'geb_dpia_automated' => false,
                    'geb_dpia_large_scale_processing' => false,
                    'geb_dpia_large_scale_monitoring' => false,
                    'geb_dpia_list_required' => false,
                ])),
            ToggleEntry::make('geb_dpia_high_risk_freedoms')
                ->label(__('avg_responsible_processing_record.geb_dpia_high_risk_freedoms'))
                ->visible(InfolistHelper::fieldValueEquals([
                    'geb_dpia_executed' => false,
                    'geb_dpia_automated' => false,
                    'geb_dpia_large_scale_processing' => false,
                    'geb_dpia_large_scale_monitoring' => false,
                    'geb_dpia_list_required' => false,
                    'geb_dpia_criteria_wp248' => false,
                ])),
            InformationBlockSection::makeCollapsible(
                __('information_blocks.avg_responsible_processing_record.step_geb_dpia_title'),
                __('information_blocks.avg_responsible_processing_record.step_geb_dpia_info'),
                __('information_blocks.avg_responsible_processing_record.step_geb_dpia_extra_info'),
            ),
        ];
    }

    /**
     * @return array<Component>
     */
    public static function getContactPerson(): array
    {
        return [
            ProcessingRecordContactPersons::makeGroup(),
            InformationBlockSection::makeCollapsible(
                __('information_blocks.avg_responsible_processing_record.step_contact_person_title'),
                __('information_blocks.avg_responsible_processing_record.step_contact_person_info'),
            ),
        ];
    }

    /**
     * @return array<Component>
     */
    public static function getAttachments(): array
    {
        return [
            SelectMultipleEntry::make('documents.name')
                ->label(__('document.model_plural')),
            InformationBlockSection::makeCollapsible(
                __('information_blocks.avg_responsible_processing_record.step_attachments_title'),
                __('information_blocks.avg_responsible_processing_record.step_attachments_info'),
            ),
        ];
    }

    /**
     * @return array<Component>
     */
    public static function getRemarks(): array
    {
        return [
            RemarksEntry::make(),
            InformationBlockSection::makeCollapsible(
                __('information_blocks.avg_responsible_processing_record.step_remarks_title'),
                __('information_blocks.avg_responsible_processing_record.step_remarks_info'),
            ),
        ];
    }

    /**
     * @return array<Component>
     */
    public static function getPublish(): array
    {
        return [
            DateTimeEntry::make('public_from')
                ->label(__('general.public_from')),
            PublicWebsiteCheckSection::makeTable(),
            InformationBlockSection::makeCollapsible(
                __('information_blocks.avg_responsible_processing_record.step_publish_title'),
                __('information_blocks.avg_responsible_processing_record.step_publish_info'),
            ),
        ];
    }
}
