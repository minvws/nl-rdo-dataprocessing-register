<?php

declare(strict_types=1);

namespace App\Filament\Resources\AlgorithmRecordResource;

use App\Filament\Forms\Components\DatePicker\DatePicker;
use App\Filament\Forms\Components\ProcessingRecordWizard;
use App\Filament\Forms\Components\Section\InformationBlockSection;
use App\Filament\Forms\Components\Select\ParentSelect;
use App\Filament\Forms\Components\Select\SelectSingleWithLookup;
use App\Filament\Forms\Components\SelectMultipleWithLookup;
use App\Filament\Forms\Components\TextInput\EntityNumber;
use App\Filament\Resources\DocumentResource\DocumentResourceForm;
use App\Models\Algorithm\AlgorithmMetaSchema;
use App\Models\Algorithm\AlgorithmPublicationCategory;
use App\Models\Algorithm\AlgorithmStatus;
use App\Models\Algorithm\AlgorithmTheme;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Form;

use function __;

class AlgorithmRecordResourceForm
{
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                ProcessingRecordWizard::make()
                    ->schema([
                        self::getStepName(),
                        self::getStepResponsibleUse(),
                        self::getStepMechanics(),
                        self::getStepMeta(),
                        self::getStepAttachments(),
                    ])
                    ->skippable()
                    ->persistStepInQueryString()
                    ->columnSpanFull(),
            ]);
    }

    private static function getStepName(): Step
    {
        return Step::make(__('algorithm_record.step_processing_name'))
            ->schema([
                EntityNumber::make()
                    ->label(__('algorithm_record.number')),
                TextInput::make('name')
                    ->label(__('general.name'))
                    ->required()
                    ->maxLength(255),
                Textarea::make('description')
                    ->label(__('algorithm_record.description')),
                SelectSingleWithLookup::makeWithDisabledOptions(
                    'algorithm_theme_id',
                    'algorithmTheme',
                    AlgorithmTheme::class,
                    'name',
                )
                    ->label(__('algorithm_record.theme')),
                SelectSingleWithLookup::makeWithDisabledOptions(
                    'algorithm_status_id',
                    'algorithmStatus',
                    AlgorithmStatus::class,
                    'name',
                )
                    ->label(__('algorithm_record.status')),
                DatePicker::make('start_date')
                    ->label(__('algorithm_record.start_date')),
                DatePicker::make('end_date')
                    ->label(__('algorithm_record.end_date')),
                TextInput::make('contact_data')
                    ->label(__('algorithm_record.contact_data')),
                TextInput::make('public_page_link')
                    ->label(__('algorithm_record.public_page_link'))
                    ->url(),
                SelectSingleWithLookup::makeWithDisabledOptions(
                    'algorithm_publication_category_id',
                    'algorithmPublicationCategory',
                    AlgorithmPublicationCategory::class,
                    'name',
                )
                    ->label(__('algorithm_record.publication_category')),
                TextInput::make('source_link')
                    ->label(__('algorithm_record.source_link'))
                    ->columns(1)
                    ->url(),
                ParentSelect::make()
                    ->hintIcon('heroicon-o-information-circle', __('general.parent_hint_icon_text')),
                InformationBlockSection::makeCollapsible(
                    __('information_blocks.algorithm_record.step_processing_name_title'),
                    __('information_blocks.algorithm_record.step_processing_name_info'),
                ),
            ]);
    }

    private static function getStepResponsibleUse(): Step
    {
        return Step::make(__('algorithm_record.step_responsible_use'))
            ->schema([
                Textarea::make('resp_goal_and_impact')
                    ->label(__('algorithm_record.resp_goal_and_impact')),
                Textarea::make('resp_considerations')
                    ->label(__('algorithm_record.resp_considerations')),
                Textarea::make('resp_human_intervention')
                    ->label(__('algorithm_record.resp_human_intervention')),
                Textarea::make('resp_risk_analysis')
                    ->label(__('algorithm_record.resp_risk_analysis')),
                Textarea::make('resp_legal_base')
                    ->label(__('algorithm_record.resp_legal_base')),
                TextInput::make('resp_legal_base_link')
                    ->label(__('algorithm_record.resp_legal_base_link'))
                    ->url(),
                TextInput::make('resp_processor_registry_link')
                    ->label(__('algorithm_record.resp_processor_registry_link'))
                    ->url(),
                Textarea::make('resp_impact_tests')
                    ->label(__('algorithm_record.resp_impact_tests')),
                Textarea::make('resp_impact_test_links')
                    ->label(__('algorithm_record.resp_impact_test_links')),
                Textarea::make('resp_impact_tests_description')
                    ->label(__('algorithm_record.resp_impact_tests_description')),
                InformationBlockSection::makeCollapsible(
                    __('information_blocks.algorithm_record.step_responsible_use_title'),
                    __('information_blocks.algorithm_record.step_responsible_use_info'),
                ),
            ]);
    }

    private static function getStepMechanics(): Step
    {
        return Step::make(__('algorithm_record.step_mechanics'))
            ->schema([
                Textarea::make('oper_data')
                    ->label(__('algorithm_record.oper_data')),
                Textarea::make('oper_links')
                    ->label(__('algorithm_record.oper_links')),
                Textarea::make('oper_technical_operation')
                    ->label(__('algorithm_record.oper_technical_operation')),
                Textarea::make('oper_supplier')
                    ->label(__('algorithm_record.oper_supplier')),
                TextInput::make('oper_source_code_link')
                    ->label(__('algorithm_record.oper_source_code_link'))
                    ->url(),
                InformationBlockSection::makeCollapsible(
                    __('information_blocks.algorithm_record.step_mechanics_title'),
                    __('information_blocks.algorithm_record.step_mechanics_info'),
                ),
            ]);
    }

    private static function getStepMeta(): Step
    {
        return Step::make(__('algorithm_record.step_meta'))
            ->schema([
                TextInput::make('meta_lang')
                    ->label(__('algorithm_record.meta_lang')),
                SelectSingleWithLookup::makeWithDisabledOptions(
                    'algorithm_meta_schema_id',
                    'algorithmMetaSchema',
                    AlgorithmMetaSchema::class,
                    'name',
                )
                    ->label(__('algorithm_record.meta_schema')),
                TextInput::make('meta_national_id')
                    ->label(__('algorithm_record.meta_national_id')),
                TextInput::make('meta_source_id')
                    ->label(__('algorithm_record.meta_source_id')),
                Textarea::make('meta_tags')
                    ->label(__('algorithm_record.meta_tags')),
                InformationBlockSection::makeCollapsible(
                    __('information_blocks.algorithm_record.step_meta_title'),
                    __('information_blocks.algorithm_record.step_meta_info'),
                ),
            ]);
    }

    private static function getStepAttachments(): Step
    {
        return Step::make(__('algorithm_record.step_attachments'))
            ->schema([
                SelectMultipleWithLookup::makeForRelationshipWithCreate(
                    'document_id',
                    'documents',
                    DocumentResourceForm::getSchema(),
                    'name',
                )
                    ->label(__('document.model_plural')),
                InformationBlockSection::makeCollapsible(
                    __('information_blocks.algorithm_record.step_attachments_title'),
                    __('information_blocks.algorithm_record.step_attachments_info'),
                ),
            ]);
    }
}
