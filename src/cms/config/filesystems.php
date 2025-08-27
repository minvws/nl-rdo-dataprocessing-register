<?php

declare(strict_types=1);

use Webmozart\Assert\Assert;

$sharedStoragePath = env('FILESYSTEM_SHARED_STORAGE_PATH', storage_path('app/shared-storage'));
Assert::string($sharedStoragePath);

$publicWebsiteRoot = env('FILESYSTEM_PUBLIC_WEBSITE_ROOT', storage_path('app/public-website'));
Assert::string($publicWebsiteRoot);

$staticWebsiteRoot = env('FILESYSTEM_STATIC_WEBSITE_ROOT', storage_path('app/static-website'));
Assert::string($staticWebsiteRoot);

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application. Just store away!
    |
    */

    'default' => 'app',

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been set up for each driver as an example of the required values.
    |
    | Supported Drivers: "local", "ftp", "sftp", "s3"
    |
    */

    'disks' => [

        // default disk for (temporary) storage, will not be shared between releases
        'app' => [
            'driver' => 'local',
            'root' => storage_path('app'),
            'throw' => false,
        ],

        // used by filament, .e.g export
        'filament' => [
            'driver' => 'local',
            'root' => sprintf('%s/filament', $sharedStoragePath),
            'visibility' => 'public',
            'throw' => false,
        ],

        // used for public-website output (see config/public-website.php)
        'public-website' => [
            'driver' => 'local',
            'root' => $publicWebsiteRoot,
            'throw' => false,
        ],

        // used for public-website output (see config/static-website.php)
        'static-website' => [
            'driver' => 'local',
            'root' => $staticWebsiteRoot,
            'throw' => false,
        ],

        // used for uploading images (see config/media-library.php)
        'media-library' => [
            'driver' => 'local',
            'root' => sprintf('%s/media-library', $sharedStoragePath),
            'visibility' => 'private',
            'throw' => false,
        ],

        // used to store generated sql-migration files (see config/sql-generator.php)
        'sql-generation' => [
            'driver' => 'local',
            'root' => database_path('sql'),
            'throw' => false,
        ],

         // only used to access the .db-requirements file in a docker-setup as volume (see config/sql-generator.php)
        'sql-generation-management' => [
            'driver' => 'local',
            'root' => storage_path('app/sql-generation-management'),
            'throw' => false,
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Symbolic Links
    |--------------------------------------------------------------------------
    |
    | Here you may configure the symbolic links that will be created when the
    | `storage:link` Artisan command is executed. The array keys should be
    | the locations of the links and the values should be their targets.
    |
    */

    'links' => [
        // see env(PUBLIC_WEBSITE_BASE_URL) for the path, and config/public_website.php -> public_website_folder for the folder-suffix
        public_path('public-website') => sprintf('%s/public-website', $publicWebsiteRoot),
        public_path('static-website') => sprintf('%s/static-website', $staticWebsiteRoot),
    ],
];
