<?php

declare(strict_types=1);

return [
    // the default content generator (options: "hugo", "fake")
    'filesystem' => env('PUBLIC_WEBSITE_FILESYSTEM', 'hugo'),

    // the default content generator (options: "hugo", "fake")
    'public_website_generator' => env('PUBLIC_WEBSITE_GENERATOR', 'hugo'),

    // the disk to use for the content & website
    'hugo_filesystem_disk' => 'public-website',

    // the folder on the disk, used for public-content
    'hugo_content_folder' => 'public-content',

    // the folder on the disk, used for the public website
    'public_website_folder' => 'public-website',

    // This is where the hugo executable, config and templates are stored that are used to generate the public website
    'hugo_project_path' => base_path('public-website'),

    // the base url for the public website
    'base_url' => env('PUBLIC_WEBSITE_BASE_URL'),
    'check_base_url' => env('PUBLIC_WEBSITE_CHECK_BASE_URL', env('PUBLIC_WEBSITE_BASE_URL')),
    'check_proxy' => env('PUBLIC_WEBSITE_CHECK_PROXY'),

    // the command to be executed after a successful build of the website
    'build_after_hook' => env('PUBLIC_WEBSITE_BUILD_AFTER_HOOK'),

    // plan jobs to check deployments after x minutes
    'plan-check-job-delays' => [1, 2, 3, 5, 10],
];
