<?php

declare(strict_types=1);

use App\Services\PublicWebsite\BuildException;
use App\Services\PublicWebsite\HugoPublicWebsiteGenerator;
use Illuminate\Process\PendingProcess;
use Illuminate\Support\Facades\Process;
use Tests\Helpers\ConfigHelper;

it('can run the hugo command', function (): void {
    $filesystemDiskPublicWebsiteRoot = storage_path('app/public-website');
    $sourceFolder = fake()->slug(1);
    $destinationFolder = fake()->slug(1);
    $baseUrl = fake()->url();

    ConfigHelper::set('filesystems.disks.public-website.root', $filesystemDiskPublicWebsiteRoot);
    ConfigHelper::set('public-website.hugo_content_folder', $sourceFolder);
    ConfigHelper::set('public-website.public_website_folder', $destinationFolder);
    ConfigHelper::set('public-website.hugo_filesystem_disk', 'public-website');
    ConfigHelper::set('public-website.base_url', $baseUrl);

    $process = Process::fake();

    /** @var HugoPublicWebsiteGenerator $hugoWebsiteGenerator */
    $hugoWebsiteGenerator = $this->app->get(HugoPublicWebsiteGenerator::class);
    $hugoWebsiteGenerator->generate();
    $process->assertRan(
        static function (PendingProcess $pendingProcess) use (
            $filesystemDiskPublicWebsiteRoot,
            $sourceFolder,
            $destinationFolder,
            $baseUrl,
        ): bool {
            return $pendingProcess->command === sprintf(
                'hugo -c %s -d %s -b %s -t rijkshuisstijl --cleanDestinationDir',
                sprintf('%s/%s', $filesystemDiskPublicWebsiteRoot, $sourceFolder),
                sprintf('%s/%s', $filesystemDiskPublicWebsiteRoot, $destinationFolder),
                $baseUrl,
            );
        },
    );
});

it('will throw a buildException', function (): void {
    Process::fake(['*' => Process::result(fake()->sentence(), fake()->sentence(), 1)]);

    /** @var HugoPublicWebsiteGenerator $hugoWebsiteGenerator */
    $hugoWebsiteGenerator = $this->app->get(HugoPublicWebsiteGenerator::class);

    $hugoWebsiteGenerator->generate();
})->expectException(BuildException::class);

it('will trigger the build-after-hook', function (): void {
    $buildAfterHook = fake()->word();
    ConfigHelper::set('public-website.build_after_hook', $buildAfterHook);

    $process = Process::fake();

    /** @var HugoPublicWebsiteGenerator $hugoWebsiteGenerator */
    $hugoWebsiteGenerator = $this->app->get(HugoPublicWebsiteGenerator::class);
    $hugoWebsiteGenerator->generate();

    $process->assertRan($buildAfterHook);
});

it('can handle a failed build-after-hook', function (): void {
    $buildAfterHook = fake()->word();
    ConfigHelper::set('public-website.build_after_hook', $buildAfterHook);

    $process = Process::fake([
        'hugo *' => Process::result(fake()->sentence(), fake()->sentence(), 0),
        $buildAfterHook => Process::result(fake()->sentence(), fake()->sentence(), 1),
    ]);

    /** @var HugoPublicWebsiteGenerator $hugoWebsiteGenerator */
    $hugoWebsiteGenerator = $this->app->get(HugoPublicWebsiteGenerator::class);
    $hugoWebsiteGenerator->generate();

    $process->assertRan($buildAfterHook);
});
