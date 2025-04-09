<?php

declare(strict_types=1);

use App\Jobs\Media\MarkMediaUploadAsValidated;
use App\Jobs\Media\PruneMetaData;
use App\Jobs\Media\ValidateMimeType;
use App\Vendor\MediaLibrary\Media;
use App\Vendor\MediaLibrary\OrganisationAwarePathGenerator;
use App\Vendor\MediaLibrary\PrivateUrlGenerator;

return [
    'path_generator' => OrganisationAwarePathGenerator::class,
    'url_generator' => PrivateUrlGenerator::class,
    'moves_media_on_update' => true,
    'media_model' => Media::class,
    'filesystem_disk' => 'media-library',

    'permitted_file_types' => [
        'attachment' => [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',

            'text/plain',
            'text/csv',

            'image/png',
            'image/jpeg',
        ],
    ],

    'post_media_upload_job_chain' => [
        PruneMetaData::class,
        ValidateMimeType::class,
        MarkMediaUploadAsValidated::class,
    ],
];
