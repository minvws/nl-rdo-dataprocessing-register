<?php

declare(strict_types=1);

use App\Services\PublicWebsite\BuildException;
use App\Services\PublicWebsite\HugoPublicWebsiteGenerator;
use Illuminate\Process\PendingProcess;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Storage;
use Tests\Helpers\ConfigTestHelper;

it('can run the hugo command', function (): void {
    $filesystemDiskPublicWebsiteRoot = fake()->slug(1);
    $sourceFolder = fake()->slug(1);
    $destinationFolder = fake()->slug(1);
    $baseUrl = fake()->url();

    ConfigTestHelper::set('filesystems.disks.public-website.root', $filesystemDiskPublicWebsiteRoot);
    ConfigTestHelper::set('public-website.hugo_content_folder', $sourceFolder);
    ConfigTestHelper::set('public-website.public_website_folder', $destinationFolder);
    ConfigTestHelper::set('public-website.hugo_filesystem_disk', 'public-website');
    ConfigTestHelper::set('public-website.base_url', $baseUrl);

    $process = Process::fake();

    $hugoWebsiteGenerator = $this->app->get(HugoPublicWebsiteGenerator::class);
    $hugoWebsiteGenerator->generate();
    $process->assertRan(
        static function (PendingProcess $pendingProcess) use (
            $sourceFolder,
            $destinationFolder,
            $baseUrl,
        ): bool {
            return $pendingProcess->command === sprintf(
                'hugo -c %s -d %s -b %s -t rijkshuisstijl --cleanDestinationDir',
                Storage::disk('public-website')->path($sourceFolder),
                Storage::disk('public-website')->path($destinationFolder),
                $baseUrl,
            );
        },
    );
});

it('will throw a buildException', function (): void {
    Process::fake([
        '*' => Process::result(fake()->sentence(), fake()->sentence(), 1),
    ]);

    /** @var HugoPublicWebsiteGenerator $hugoWebsiteGenerator */
    $hugoWebsiteGenerator = $this->app->get(HugoPublicWebsiteGenerator::class);

    $hugoWebsiteGenerator->generate();
})->throws(BuildException::class);

it('will run the build-after-hook', function (): void {
    $buildAfterHook = fake()->word();
    ConfigTestHelper::set('public-website.build_after_hook', $buildAfterHook);

    $process = Process::fake();

    /** @var HugoPublicWebsiteGenerator $hugoWebsiteGenerator */
    $hugoWebsiteGenerator = $this->app->get(HugoPublicWebsiteGenerator::class);
    $hugoWebsiteGenerator->generate();

    $process->assertRan($buildAfterHook);
});

it('can handle a failed build-after-hook', function (): void {
    $buildAfterHook = fake()->word();
    ConfigTestHelper::set('public-website.build_after_hook', $buildAfterHook);

    $process = Process::fake([
        'hugo *' => Process::result(fake()->sentence(), fake()->sentence(), 0),
        $buildAfterHook => Process::result(fake()->sentence(), fake()->sentence(), 1),
    ]);

    /** @var HugoPublicWebsiteGenerator $hugoWebsiteGenerator */
    $hugoWebsiteGenerator = $this->app->get(HugoPublicWebsiteGenerator::class);
    $hugoWebsiteGenerator->generate();

    $process->assertRan($buildAfterHook);
});
