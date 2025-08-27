<?php

declare(strict_types=1);

namespace App\Filament\Resources\DataBreachRecord;

use App\Filament\Infolists\Components\ProcessingRecordTabs;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Tabs\Tab;
use Filament\Infolists\Infolist;

use function __;

class DataBreachRecordResourceInfolist
{
    public static function stepsInfolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->columns(1)
            ->extraAttributes(['class' => 'vertical'])
            ->schema([
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

    public static function onePageInfolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
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
