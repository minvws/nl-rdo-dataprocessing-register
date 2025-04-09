<?php

declare(strict_types=1);

use App\Import\Factories\Avg\AvgProcessorProcessingRecordFactory;
use App\Import\Factories\Avg\AvgResponsibleProcessingRecordFactory;
use App\Import\Factories\Wpg\WpgProcessingRecordFactory;
use App\Import\Importers\JsonImporter;
use App\Models\States\Snapshot\Approved;
use App\Models\States\Snapshot\Established;
use App\Models\States\Snapshot\InReview;

return [
    'date' => [
        'expectedFormats' => [
            'Y-m-d\TH:i:s.v',
            'Y-m-d\TH:i:s',
        ],
        'timezone' => 'Europe/Amsterdam',
    ],

    'factories' => [
        'AVG Verantwoordelijke Verwerkingen' => AvgResponsibleProcessingRecordFactory::class,
        'AVG Verwerker Verwerkingen' => AvgProcessorProcessingRecordFactory::class,
        'WPG Verantwoordelijke Verwerkingen' => WpgProcessingRecordFactory::class,
    ],

    'importers' => [
        'json' => JsonImporter::class,
    ],

    'max_zipped_file_filesize_in_mb' => 10,
    'max_number_of_files_in_zip' => 100,

    'states_to_skip_import' => [
        'Vervallen',
    ],

    'value_converters' => [
        'boolean_true' => [
            'ja',
            'true',
            'yes',
        ],
        'snapshot_state' => [
            'TerReview' => InReview::class,
            'VaststellingAangevraagd' => Approved::class,
            'Vastgesteld' => Established::class,
        ],
    ],

    'zip' => [
        'max_zipped_filesize_in_mb' => 10,
        'max_zipped_number_of_files' => 100,
    ],
];
