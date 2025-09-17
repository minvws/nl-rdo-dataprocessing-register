<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Tests\Helpers\ConfigTestHelper;
use Tests\TestCase;

abstract class FeatureTestCase extends TestCase
{
    use DatabaseTransactions;
    use WithFilament;
    use WithLivewire;

    protected function setUp(): void
    {
        parent::setUp();

        Http::fake();

        Storage::fake('tmp-for-tests');
        Storage::fake(ConfigTestHelper::get('static-website.hugo_filesystem_disk'));
    }
}
