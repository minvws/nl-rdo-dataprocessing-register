<?php

declare(strict_types=1);

namespace App\Filament\Resources\AlgorithmRecordResource;

use App\Filament\Infolists\Components\ProcessingRecordTabs;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

use function __;

class AlgorithmRecordResourceInfolist
{
    public static function stepsInfolist(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                ProcessingRecordTabs::make()
                    ->tabs([
                        Tab::make(__('algorithm_record.step_processing_name'))
                            ->schema(AlgorithmRecordResourceInfolistSchemas::getProcessingName()),
                        Tab::make(__('algorithm_record.step_responsible_use'))
                            ->schema(AlgorithmRecordResourceInfolistSchemas::getResponsibleUse()),
                        Tab::make(__('algorithm_record.step_mechanics'))
                            ->schema(AlgorithmRecordResourceInfolistSchemas::getMechanics()),
                        Tab::make(__('algorithm_record.step_meta'))
                            ->schema(AlgorithmRecordResourceInfolistSchemas::getMeta()),
                        Tab::make(__('algorithm_record.step_impact'))
                            ->schema(AlgorithmRecordResourceInfolistSchemas::getImpact()),
                        Tab::make(__('algorithm_record.step_validation'))
                            ->schema(AlgorithmRecordResourceInfolistSchemas::getValidation()),
                        Tab::make(__('algorithm_record.step_attachments'))
                            ->schema(AlgorithmRecordResourceInfolistSchemas::getAttachments()),
                    ]),
            ]);
    }

    public static function onePageInfolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('algorithm_record.step_processing_name'))
                    ->schema(AlgorithmRecordResourceInfolistSchemas::getProcessingName())
                    ->compact()
                    ->aside(),
                Section::make(__('algorithm_record.step_responsible_use'))
                    ->schema(AlgorithmRecordResourceInfolistSchemas::getResponsibleUse())
                    ->compact()
                    ->aside(),
                Section::make(__('algorithm_record.step_mechanics'))
                    ->schema(AlgorithmRecordResourceInfolistSchemas::getMechanics())
                    ->compact()
                    ->aside(),
                Section::make(__('algorithm_record.step_meta'))
                    ->schema(AlgorithmRecordResourceInfolistSchemas::getMeta())
                    ->compact()
                    ->aside(),
                Section::make(__('algorithm_record.step_impact'))
                    ->schema(AlgorithmRecordResourceInfolistSchemas::getImpact())
                    ->compact()
                    ->aside(),
                Section::make(__('algorithm_record.step_validation'))
                    ->schema(AlgorithmRecordResourceInfolistSchemas::getValidation())
                    ->compact()
                    ->aside(),
                Section::make(__('algorithm_record.step_attachments'))
                    ->schema(AlgorithmRecordResourceInfolistSchemas::getAttachments())
                    ->compact()
                    ->aside(),
            ]);
    }
}
