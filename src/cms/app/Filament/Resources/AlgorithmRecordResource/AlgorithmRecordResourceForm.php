<?php

declare(strict_types=1);

namespace App\Filament\Resources\AlgorithmRecordResource;

use App\Filament\Forms\Components\ProcessingRecordStep;
use App\Filament\Forms\Components\ProcessingRecordWizard;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

use function __;

class AlgorithmRecordResourceForm
{
    public static function stepsForm(Schema $schema): Schema
    {
        return $schema
            ->components([
                ProcessingRecordWizard::make()
                    ->schema([
                        ProcessingRecordStep::make(__('algorithm_record.step_processing_name'))
                            ->schema(AlgorithmRecordResourceFormSchemas::getProcessingName()),
                        ProcessingRecordStep::make(__('algorithm_record.step_responsible_use'))
                            ->schema(AlgorithmRecordResourceFormSchemas::getResponsibleUse()),
                        ProcessingRecordStep::make(__('algorithm_record.step_mechanics'))
                            ->schema(AlgorithmRecordResourceFormSchemas::getMechanics()),
                        ProcessingRecordStep::make(__('algorithm_record.step_meta'))
                            ->schema(AlgorithmRecordResourceFormSchemas::getMeta()),
                        ProcessingRecordStep::make(__('algorithm_record.step_impact'))
                            ->schema(AlgorithmRecordResourceFormSchemas::getImpact()),
                        ProcessingRecordStep::make(__('algorithm_record.step_validation'))
                            ->schema(AlgorithmRecordResourceFormSchemas::getValidation()),
                        ProcessingRecordStep::make(__('algorithm_record.step_attachments'))
                            ->schema(AlgorithmRecordResourceFormSchemas::getAttachments()),
                    ])
                    ->skippable()
                    ->persistStepInQueryString()
                    ->columnSpanFull(),
            ]);
    }

    public static function onePageForm(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('algorithm_record.step_processing_name'))
                    ->schema(AlgorithmRecordResourceFormSchemas::getProcessingName())
                    ->compact()
                    ->aside(),
                Section::make(__('algorithm_record.step_responsible_use'))
                    ->schema(AlgorithmRecordResourceFormSchemas::getResponsibleUse())
                    ->compact()
                    ->aside(),
                Section::make(__('algorithm_record.step_mechanics'))
                    ->schema(AlgorithmRecordResourceFormSchemas::getMechanics())
                    ->compact()
                    ->aside(),
                Section::make(__('algorithm_record.step_meta'))
                    ->schema(AlgorithmRecordResourceFormSchemas::getMeta())
                    ->compact()
                    ->aside(),
                Section::make(__('algorithm_record.step_impact'))
                    ->schema(AlgorithmRecordResourceFormSchemas::getImpact())
                    ->compact()
                    ->aside(),
                Section::make(__('algorithm_record.step_validation'))
                    ->schema(AlgorithmRecordResourceFormSchemas::getValidation())
                    ->compact()
                    ->aside(),
                Section::make(__('algorithm_record.step_attachments'))
                    ->schema(AlgorithmRecordResourceFormSchemas::getAttachments())
                    ->compact()
                    ->aside(),
            ]);
    }
}
