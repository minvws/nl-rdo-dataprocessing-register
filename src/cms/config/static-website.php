<?php

declare(strict_types=1);

return [
    // the default content generator (options: "hugo", "fake")
    'filesystem' => env('STATIC_WEBSITE_FILESYSTEM', 'hugo'),

    // the default content generator (options: "hugo", "fake")
    'static_website_generator' => env('STATIC_WEBSITE_GENERATOR', 'hugo'),

    // the disk to use for the content & website
    'hugo_filesystem_disk' => 'static-website',

    // the folder on the disk, used for static-content
    'hugo_content_folder' => 'static-content',

    // the folder on the disk, used for the static website
    'static_website_folder' => 'static-website',

    // This is where the hugo executable, config and templates are stored that are used to generate the static website
    'hugo_project_path' => base_path('static-website'),

    // the base url for the static website
    'base_url' => env('STATIC_WEBSITE_BASE_URL'),
    'check_base_url' => env('STATIC_WEBSITE_CHECK_BASE_URL', env('STATIC_WEBSITE_BASE_URL')),
    'check_proxy' => env('STATIC_WEBSITE_CHECK_PROXY'),

    // the command to be executed after a successful build of the website
    'build_after_hook' => env('STATIC_WEBSITE_BUILD_AFTER_HOOK'),

    // plan jobs to check deployments after x minutes
    'plan-check-job-delays' => [1, 2, 3, 5, 10],
];
