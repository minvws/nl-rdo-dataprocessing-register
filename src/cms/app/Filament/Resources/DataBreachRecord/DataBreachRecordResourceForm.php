<?php

declare(strict_types=1);

namespace App\Filament\Resources\DataBreachRecord;

use App\Filament\Forms\Components\ProcessingRecordStep;
use App\Filament\Forms\Components\ProcessingRecordWizard;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

use function __;

class DataBreachRecordResourceForm
{
    public static function stepsForm(Schema $schema): Schema
    {
        return $schema
            ->components([
                ProcessingRecordWizard::make()
                    ->schema([
                        ProcessingRecordStep::make(__('data_breach_record.step_name'))
                            ->schema(DataBreachRecordResourceFormSchemas::getName()),
                        ProcessingRecordStep::make(__('data_breach_record.step_responsible'))
                            ->schema(DataBreachRecordResourceFormSchemas::getResponsible()),
                        ProcessingRecordStep::make(__('data_breach_record.step_dates'))
                            ->schema(DataBreachRecordResourceFormSchemas::getDates()),
                        ProcessingRecordStep::make(__('data_breach_record.step_incident'))
                            ->schema(DataBreachRecordResourceFormSchemas::getIncident()),
                        ProcessingRecordStep::make(__('data_breach_record.step_processing_records'))
                            ->schema(DataBreachRecordResourceFormSchemas::getProcessingRecords()),
                        ProcessingRecordStep::make(__('data_breach_record.step_attachments'))
                            ->schema(DataBreachRecordResourceFormSchemas::getAttachments()),
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
                Section::make(__('data_breach_record.step_name'))
                    ->schema(DataBreachRecordResourceFormSchemas::getName())
                    ->compact()
                    ->aside(),
                Section::make(__('data_breach_record.step_responsible'))
                    ->schema(DataBreachRecordResourceFormSchemas::getResponsible())
                    ->compact()
                    ->aside(),
                Section::make(__('data_breach_record.step_dates'))
                    ->schema(DataBreachRecordResourceFormSchemas::getDates())
                    ->compact()
                    ->aside(),
                Section::make(__('data_breach_record.step_incident'))
                    ->schema(DataBreachRecordResourceFormSchemas::getIncident())
                    ->compact()
                    ->aside(),
                Section::make(__('data_breach_record.step_processing_records'))
                    ->schema(DataBreachRecordResourceFormSchemas::getProcessingRecords())
                    ->compact()
                    ->aside(),
                Section::make(__('data_breach_record.step_attachments'))
                    ->schema(DataBreachRecordResourceFormSchemas::getAttachments())
                    ->compact()
                    ->aside(),
            ]);
    }
}
