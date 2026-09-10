<?php

declare(strict_types=1);

namespace App\Filament\Resources\DataBreachRecord;

use App\Filament\Infolists\Components\ProcessingRecordTabs;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

use function __;

class DataBreachRecordResourceInfolist
{
    public static function stepsInfolist(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                ProcessingRecordTabs::make()
                    ->tabs([
                        Tab::make(__('data_breach_record.step_name'))
                            ->schema(DataBreachRecordResourceInfolistSchemas::getName()),
                        Tab::make(__('data_breach_record.step_responsible'))
                            ->schema(DataBreachRecordResourceInfolistSchemas::getResponsible()),
                        Tab::make(__('data_breach_record.step_dates'))
                            ->schema(DataBreachRecordResourceInfolistSchemas::getDates()),
                        Tab::make(__('data_breach_record.step_incident'))
                            ->schema(DataBreachRecordResourceInfolistSchemas::getIncident()),
                        Tab::make(__('data_breach_record.step_processing_records'))
                            ->schema(DataBreachRecordResourceInfolistSchemas::getProcessingRecords()),
                        Tab::make(__('data_breach_record.step_attachments'))
                            ->schema(DataBreachRecordResourceInfolistSchemas::getAttachments()),
                    ]),
            ]);
    }

    public static function onePageInfolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('data_breach_record.step_name'))
                    ->schema(DataBreachRecordResourceInfolistSchemas::getName())
                    ->compact()
                    ->aside(),
                Section::make(__('data_breach_record.step_responsible'))
                    ->schema(DataBreachRecordResourceInfolistSchemas::getResponsible())
                    ->compact()
                    ->aside(),
                Section::make(__('data_breach_record.step_dates'))
                    ->schema(DataBreachRecordResourceInfolistSchemas::getDates())
                    ->compact()
                    ->aside(),
                Section::make(__('data_breach_record.step_incident'))
                    ->schema(DataBreachRecordResourceInfolistSchemas::getIncident())
                    ->compact()
                    ->aside(),
                Section::make(__('data_breach_record.step_processing_records'))
                    ->schema(DataBreachRecordResourceInfolistSchemas::getProcessingRecords())
                    ->compact()
                    ->aside(),
                Section::make(__('data_breach_record.step_attachments'))
                    ->schema(DataBreachRecordResourceInfolistSchemas::getAttachments())
                    ->compact()
                    ->aside(),
            ]);
    }
}
