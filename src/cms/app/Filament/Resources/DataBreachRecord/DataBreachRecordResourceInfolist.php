<?php

declare(strict_types=1);

namespace App\Filament\Resources\DataBreachRecord;

use App\Filament\Infolists\Components\DateEntry;
use App\Filament\Infolists\Components\EntityNumberEntry;
use App\Filament\Infolists\Components\ProcessingRecordTabs;
use App\Filament\Infolists\Components\Section\InformationBlockSection;
use App\Filament\Infolists\Components\SelectMultipleEntry;
use App\Filament\Infolists\Components\TextareaEntry;
use App\Filament\Infolists\Components\ToggleEntry;
use App\Filament\Infolists\InfolistHelper;
use Filament\Infolists\Components\Tabs\Tab;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;

use function __;

class DataBreachRecordResourceInfolist
{
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->columns(1)
            ->extraAttributes(['class' => 'vertical'])
            ->schema([
                ProcessingRecordTabs::make()
                    ->tabs([
                        self::getTabName(),
                        self::getTabResponsible(),
                        self::getTabDates(),
                        self::getTabIncident(),
                        self::getTabProcessingRecords(),
                        self::getTabAttachments(),
                    ]),
            ]);
    }

    private static function getTabName(): Tab
    {
        return Tab::make(__('data_breach_record.step_name'))
            ->schema([
                EntityNumberEntry::make(),
                TextEntry::make('name')
                    ->label(__('data_breach_record.name')),
                DateEntry::make('reported_at')
                    ->label(__('data_breach_record.reported_at')),
                TextEntry::make('type')
                    ->label(__('data_breach_record.type'))
                    ->placeholder(__('general.none_selected')),
                ToggleEntry::make('ap_reported')
                    ->label(__('data_breach_record.ap_reported')),
                InformationBlockSection::makeCollapsible(
                    __('information_blocks.data_breach_record.step_name_title'),
                    __('information_blocks.data_breach_record.step_name_info'),
                ),
            ]);
    }

    private static function getTabResponsible(): Tab
    {
        return Tab::make(__('data_breach_record.step_responsible'))
            ->schema([
                SelectMultipleEntry::make('responsibles.name')
                    ->label(__('responsible.model_plural')),
                InformationBlockSection::makeCollapsible(
                    __('information_blocks.data_breach_record.step_responsible_title'),
                    __('information_blocks.data_breach_record.step_responsible_info'),
                    __('information_blocks.data_breach_record.step_responsible_extra_info'),
                ),
            ]);
    }

    private static function getTabDates(): Tab
    {
        return Tab::make(__('data_breach_record.step_dates'))
            ->schema([
                DateEntry::make('discovered_at')
                    ->label(__('data_breach_record.discovered_at')),
                DateEntry::make('started_at')
                    ->label(__('data_breach_record.started_at')),
                DateEntry::make('ended_at')
                    ->label(__('data_breach_record.ended_at')),
                DateEntry::make('ap_reported_at')
                    ->label(__('data_breach_record.ap_reported_at')),
                DateEntry::make('completed_at')
                    ->label(__('data_breach_record.completed_at')),
                InformationBlockSection::makeCollapsible(
                    __('information_blocks.data_breach_record.step_dates_title'),
                    __('information_blocks.data_breach_record.step_dates_info'),
                ),
            ]);
    }

    private static function getTabIncident(): Tab
    {
        return Tab::make(__('data_breach_record.step_incident'))
            ->schema([
                TextEntry::make('nature_of_incident')
                    ->label(__('data_breach_record.nature_of_incident')),
                TextareaEntry::make('nature_of_incident_other')
                    ->label(__('data_breach_record.nature_of_incident_other'))
                    ->visible(InfolistHelper::fieldValueEquals(['nature_of_incident' => 'Overig'])),
                TextareaEntry::make('summary')
                    ->label(__('data_breach_record.summary')),
                TextareaEntry::make('involved_people')
                    ->label(__('data_breach_record.involved_people')),
                SelectMultipleEntry::make('personal_data_categories')
                    ->label(__('data_breach_record.personal_data_categories')),
                TextareaEntry::make('personal_data_categories_other')
                    ->label(__('data_breach_record.personal_data_categories_other'))
                    ->visible(InfolistHelper::fieldValuesContainValue('personal_data_categories', 'Anders')),
                SelectMultipleEntry::make('personal_data_special_categories')
                    ->label(__('data_breach_record.personal_data_special_categories')),
                TextareaEntry::make('estimated_risk')
                    ->label(__('data_breach_record.estimated_risk')),
                TextareaEntry::make('measures')
                    ->label(__('data_breach_record.measures')),
                ToggleEntry::make('reported_to_involved')
                    ->label(__('data_breach_record.reported_to_involved')),
                SelectMultipleEntry::make('reported_to_involved_communication')
                    ->label(__('data_breach_record.reported_to_involved_communication'))
                    ->visible(InfolistHelper::isFieldEnabled('reported_to_involved')),
                TextareaEntry::make('reported_to_involved_communication_other')
                    ->label(__('data_breach_record.reported_to_involved_communication_other'))
                    ->visible(InfolistHelper::fieldValuesContainValue('reported_to_involved_communication', 'Anders')),
                ToggleEntry::make('fg_reported')
                    ->label(__('data_breach_record.fg_reported')),
                InformationBlockSection::makeCollapsible(
                    __('information_blocks.data_breach_record.step_incident_title'),
                    __('information_blocks.data_breach_record.step_incident_info'),
                ),
            ]);
    }

    private static function getTabProcessingRecords(): Tab
    {
        return Tab::make(__('data_breach_record.step_processing_records'))
            ->schema([
                SelectMultipleEntry::make('avgResponsibleProcessingRecords.name')
                    ->label(__('avg_responsible_processing_record.model_plural')),
                SelectMultipleEntry::make('avgProcessorProcessingRecords.name')
                    ->label(__('avg_processor_processing_record.model_plural')),
                SelectMultipleEntry::make('wpgProcessingRecords.name')
                    ->label(__('wpg_processing_record.model_plural')),
                InformationBlockSection::makeCollapsible(
                    __('information_blocks.data_breach_record.step_processing_records_title'),
                    __('information_blocks.data_breach_record.step_processing_records_info'),
                ),
            ]);
    }

    private static function getTabAttachments(): Tab
    {
        return Tab::make(__('data_breach_record.step_attachments'))
            ->schema([
                SelectMultipleEntry::make('documents.name')
                    ->label(__('document.model_plural')),
                InformationBlockSection::makeCollapsible(
                    __('information_blocks.data_breach_record.step_attachments_title'),
                    __('information_blocks.data_breach_record.step_attachments_info'),
                ),
            ]);
    }
}
