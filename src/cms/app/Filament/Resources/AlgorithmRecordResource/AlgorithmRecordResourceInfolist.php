<?php

declare(strict_types=1);

namespace App\Filament\Resources\AlgorithmRecordResource;

use App\Filament\Infolists\Components\DateEntry;
use App\Filament\Infolists\Components\EntityNumberEntry;
use App\Filament\Infolists\Components\ParentSelectEntry;
use App\Filament\Infolists\Components\ProcessingRecordTabs;
use App\Filament\Infolists\Components\Section\InformationBlockSection;
use App\Filament\Infolists\Components\SelectMultipleEntry;
use App\Filament\Infolists\Components\TextareaEntry;
use Filament\Infolists\Components\Tabs\Tab;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;

use function __;

class AlgorithmRecordResourceInfolist
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
                        self::getTabResponsibleUse(),
                        self::getTabMechanics(),
                        self::getTabMeta(),
                        self::getTabAttachments(),
                    ]),
            ]);
    }

    private static function getTabName(): Tab
    {
        return Tab::make(__('algorithm_record.step_processing_name'))
            ->schema([
                EntityNumberEntry::make(),
                TextEntry::make('name')
                    ->label(__('general.name')),
                TextareaEntry::make('description')
                    ->label(__('algorithm_record.description')),
                TextEntry::make('algorithmTheme.name')
                    ->label(__('algorithm_record.theme')),
                TextEntry::make('algorithmStatus.name')
                    ->label(__('algorithm_record.status')),
                DateEntry::make('start_date')
                    ->label(__('algorithm_record.start_date')),
                DateEntry::make('end_date')
                    ->label(__('algorithm_record.end_date')),
                TextEntry::make('contact_data')
                    ->label(__('algorithm_record.contact_data')),
                TextEntry::make('public_page_link')
                    ->label(__('algorithm_record.public_page_link')),
                TextEntry::make('algorithmPublicationCategory.name')
                    ->label(__('algorithm_record.publication_category')),
                TextEntry::make('source_link')
                    ->label(__('algorithm_record.source_link')),
                ParentSelectEntry::make(),
                InformationBlockSection::makeCollapsible(
                    __('information_blocks.algorithm_record.step_processing_name_title'),
                    __('information_blocks.algorithm_record.step_processing_name_info'),
                ),
            ]);
    }

    private static function getTabResponsibleUse(): Tab
    {
        return Tab::make(__('algorithm_record.step_responsible_use'))
            ->schema([
                TextareaEntry::make('resp_goal_and_impact')
                    ->label(__('algorithm_record.resp_goal_and_impact')),
                TextareaEntry::make('resp_considerations')
                    ->label(__('algorithm_record.resp_considerations')),
                TextareaEntry::make('resp_human_intervention')
                    ->label(__('algorithm_record.resp_human_intervention')),
                TextareaEntry::make('resp_risk_analysis')
                    ->label(__('algorithm_record.resp_risk_analysis')),
                TextareaEntry::make('resp_legal_base')
                    ->label(__('algorithm_record.resp_legal_base')),
                TextEntry::make('resp_legal_base_link')
                    ->label(__('algorithm_record.resp_legal_base_link')),
                TextEntry::make('resp_processor_registry_link')
                    ->label(__('algorithm_record.resp_processor_registry_link')),
                TextareaEntry::make('resp_impact_tests')
                    ->label(__('algorithm_record.resp_impact_tests')),
                TextareaEntry::make('resp_impact_test_links')
                    ->label(__('algorithm_record.resp_impact_test_links')),
                TextareaEntry::make('resp_impact_tests_description')
                    ->label(__('algorithm_record.resp_impact_tests_description')),
                InformationBlockSection::makeCollapsible(
                    __('information_blocks.algorithm_record.step_responsible_use_title'),
                    __('information_blocks.algorithm_record.step_responsible_use_info'),
                ),
            ]);
    }

    private static function getTabMechanics(): Tab
    {
        return Tab::make(__('algorithm_record.step_mechanics'))
            ->schema([
                TextareaEntry::make('oper_data')
                    ->label(__('algorithm_record.oper_data')),
                TextareaEntry::make('oper_links')
                    ->label(__('algorithm_record.oper_links')),
                TextareaEntry::make('oper_technical_operation')
                    ->label(__('algorithm_record.oper_technical_operation')),
                TextareaEntry::make('oper_supplier')
                    ->label(__('algorithm_record.oper_supplier')),
                TextEntry::make('oper_source_code_link')
                    ->label(__('algorithm_record.oper_source_code_link')),
                InformationBlockSection::makeCollapsible(
                    __('information_blocks.algorithm_record.step_mechanics_title'),
                    __('information_blocks.algorithm_record.step_mechanics_info'),
                ),
            ]);
    }

    private static function getTabMeta(): Tab
    {
        return Tab::make(__('algorithm_record.step_meta'))
            ->schema([
                TextEntry::make('meta_lang')
                    ->label(__('algorithm_record.meta_lang')),
                TextEntry::make('algorithmMetaSchema.name')
                    ->label(__('algorithm_record.meta_schema')),
                TextEntry::make('meta_national_id')
                    ->label(__('algorithm_record.meta_national_id')),
                TextEntry::make('meta_source_id')
                    ->label(__('algorithm_record.meta_source_id')),
                TextareaEntry::make('meta_tags')
                    ->label(__('algorithm_record.meta_tags')),
                InformationBlockSection::makeCollapsible(
                    __('information_blocks.algorithm_record.step_meta_title'),
                    __('information_blocks.algorithm_record.step_meta_info'),
                ),
            ]);
    }

    private static function getTabAttachments(): Tab
    {
        return Tab::make(__('algorithm_record.step_attachments'))
            ->schema([
                SelectMultipleEntry::make('documents.name')
                    ->label(__('document.model_plural')),
                InformationBlockSection::makeCollapsible(
                    __('information_blocks.algorithm_record.step_attachments_title'),
                    __('information_blocks.algorithm_record.step_attachments_info'),
                ),
            ]);
    }
}
