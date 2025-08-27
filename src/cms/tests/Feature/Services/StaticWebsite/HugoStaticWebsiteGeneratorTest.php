<?php

declare(strict_types=1);

use App\Services\StaticWebsite\BuildException;
use App\Services\StaticWebsite\HugoStaticWebsiteGenerator;
use Illuminate\Process\PendingProcess;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Console\Command\Command;
use Tests\Helpers\ConfigTestHelper;

it('can run the hugo command', function (): void {
    $filesystemDiskStaticWebsiteRoot = fake()->slug(1);
    $sourceFolder = fake()->slug(1);
    $destinationFolder = fake()->slug(1);
    $baseUrl = fake()->url();

    ConfigTestHelper::set('filesystems.disks.static-website.root', $filesystemDiskStaticWebsiteRoot);
    ConfigTestHelper::set('static-website.hugo_content_folder', $sourceFolder);
    ConfigTestHelper::set('static-website.static_website_folder', $destinationFolder);
    ConfigTestHelper::set('static-website.hugo_filesystem_disk', 'static-website');
    ConfigTestHelper::set('static-website.base_url', $baseUrl);

    $process = Process::fake();

    $hugoWebsiteGenerator = $this->app->get(HugoStaticWebsiteGenerator::class);
    $hugoWebsiteGenerator->generate();
    $process->assertRan(
        static function (PendingProcess $pendingProcess) use (
            $sourceFolder,
            $destinationFolder,
            $baseUrl,
        ): bool {
            return $pendingProcess->command === sprintf(
                'hugo -c %s -d %s -b %s -t rijkshuisstijl --cleanDestinationDir',
                Storage::disk('static-website')->path($sourceFolder),
                Storage::disk('static-website')->path($destinationFolder),
                $baseUrl,
            );
        },
    );
});

it('will throw a buildException', function (): void {
    Process::fake([
        '*' => Process::result(fake()->sentence(), fake()->sentence(), Command::FAILURE),
    ]);

    /** @var HugoStaticWebsiteGenerator $hugoWebsiteGenerator */
    $hugoWebsiteGenerator = $this->app->get(HugoStaticWebsiteGenerator::class);

    $hugoWebsiteGenerator->generate();
})->throws(BuildException::class);

it('will run the build-after-hook', function (): void {
    $buildAfterHook = fake()->word();
    ConfigTestHelper::set('static-website.build_after_hook', $buildAfterHook);

    $process = Process::fake();

    /** @var HugoStaticWebsiteGenerator $hugoWebsiteGenerator */
    $hugoWebsiteGenerator = $this->app->get(HugoStaticWebsiteGenerator::class);
    $hugoWebsiteGenerator->generate();

    $process->assertRan($buildAfterHook);
});

it('can handle a failed build-after-hook', function (): void {
    $buildAfterHook = fake()->word();
    ConfigTestHelper::set('static-website.build_after_hook', $buildAfterHook);

    $process = Process::fake([
        'hugo *' => Process::result(fake()->sentence(), fake()->sentence(), Command::SUCCESS),
        $buildAfterHook => Process::result(fake()->sentence(), fake()->sentence(), Command::FAILURE),
    ]);

    /** @var HugoStaticWebsiteGenerator $hugoWebsiteGenerator */
    $hugoWebsiteGenerator = $this->app->get(HugoStaticWebsiteGenerator::class);
    $hugoWebsiteGenerator->generate();

    $process->assertRan($buildAfterHook);
});
