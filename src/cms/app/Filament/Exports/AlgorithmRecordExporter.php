<?php

declare(strict_types=1);

namespace App\Filament\Exports;

use App\Filament\Actions\Exports\ExportColumn;
use App\Filament\Actions\Exports\SnapshotLatestCreatedAtColumn;
use App\Filament\Actions\Exports\SnapshotLatestEstablishedColumn;
use App\Filament\Actions\Exports\SnapshotLatestStatusColumn;
use App\Models\Algorithm\AlgorithmRecord;

use function __;

class AlgorithmRecordExporter extends Exporter
{
    protected static ?string $model = AlgorithmRecord::class;

    public static function getColumns(): array
    {
        return [
            // naam verwerking
            ExportColumn::make('organisation.name')
                ->label(__('organisation.model_singular')),
            ExportColumn::make('organisation.responsibleLegalEntity.name')
                ->label(__('responsible_legal_entity.model_singular')),
            ExportColumn::make('entityNumber.number')
                ->label(__('algorithm_record.number')),
            ExportColumn::make('name')
                ->label(__('general.name')),
            ExportColumn::make('description')
                ->label(__('algorithm_record.description')),
            ExportColumn::make('algorithmMetaSchema.name')
                ->label(__('algorithm_meta_schema.model_singular')),
            ExportColumn::make('algorithmPublicationCategory.name')
                ->label(__('algorithm_publication_category.model_singular')),
            ExportColumn::make('algorithmStatus.name')
                ->label(__('algorithm_status.model_singular')),
            ExportColumn::make('algorithmTheme.name')
                ->label(__('algorithm_theme.model_singular')),
            ExportColumn::make('start_date')
                ->label(__('algorithm_record.start_date')),
            ExportColumn::make('end_date')
                ->label(__('algorithm_record.end_date')),
            ExportColumn::make('contact_data')
                ->label(__('algorithm_record.contact_data')),
            ExportColumn::make('public_page_link')
                ->label(__('algorithm_record.public_page_link')),
            ExportColumn::make('source_link')
                ->label(__('algorithm_record.source_link')),

            // verantwoord gebruik
            ExportColumn::make('resp_goal_and_impact')
                ->label(__('algorithm_record.resp_goal_and_impact')),
            ExportColumn::make('resp_considerations')
                ->label(__('algorithm_record.resp_considerations')),
            ExportColumn::make('resp_human_intervention')
                ->label(__('algorithm_record.resp_human_intervention')),
            ExportColumn::make('resp_risk_analysis')
                ->label(__('algorithm_record.resp_risk_analysis')),
            ExportColumn::make('resp_legal_base_title')
                ->label(__('algorithm_record.resp_legal_base_title')),
            ExportColumn::make('resp_legal_base')
                ->label(__('algorithm_record.resp_legal_base')),
            ExportColumn::make('resp_legal_base_link')
                ->label(__('algorithm_record.resp_legal_base_link')),
            ExportColumn::make('resp_processor_registry_link')
                ->label(__('algorithm_record.resp_processor_registry_link')),
            ExportColumn::make('resp_impact_tests')
                ->label(__('algorithm_record.resp_impact_tests')),
            ExportColumn::make('resp_impact_test_links')
                ->label(__('algorithm_record.resp_impact_test_links')),
            ExportColumn::make('resp_impact_tests_description')
                ->label(__('algorithm_record.resp_impact_tests_description')),

            // werking
            ExportColumn::make('oper_data_title')
                ->label(__('algorithm_record.oper_data_title')),
            ExportColumn::make('oper_data')
                ->label(__('algorithm_record.oper_data')),
            ExportColumn::make('oper_links')
                ->label(__('algorithm_record.oper_links')),
            ExportColumn::make('oper_technical_operation')
                ->label(__('algorithm_record.oper_technical_operation')),
            ExportColumn::make('oper_supplier')
                ->label(__('algorithm_record.oper_supplier')),
            ExportColumn::make('oper_source_code_link')
                ->label(__('algorithm_record.oper_source_code_link')),

            // metadata
            ExportColumn::make('meta_lang')
                ->label(__('algorithm_record.meta_lang')),
            ExportColumn::make('meta_national_id')
                ->label(__('algorithm_record.meta_national_id')),
            ExportColumn::make('meta_source_id')
                ->label(__('algorithm_record.meta_source_id')),
            ExportColumn::make('meta_tags')
                ->label(__('algorithm_record.meta_tags')),

            // documenten
            ExportColumn::make('document_count')
                ->label(__('document.model_plural'))
                ->counts('documents'),

            // overig
            ExportColumn::make('created_at')
                ->label(__('general.created_at')),
            ExportColumn::make('updated_at')
                ->label(__('general.updated_at')),
            SnapshotLatestEstablishedColumn::make(),
            SnapshotLatestStatusColumn::make(),
            SnapshotLatestCreatedAtColumn::make(),
        ];
    }
}
