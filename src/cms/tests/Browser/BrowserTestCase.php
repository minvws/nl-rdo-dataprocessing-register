<?php

declare(strict_types=1);

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Vite;
use Tests\Helpers\ConfigTestHelper;
use Tests\TestCase;

use function storage_path;

abstract class BrowserTestCase extends TestCase
{
    use DatabaseTransactions;
    use WithAxe;
    use WithBrowserAuthentication;

    protected function setUp(): void
    {
        parent::setUp();

        ConfigTestHelper::set('auth.one_time_password.driver', 'fake');

        // The test browser cannot reach a Vite dev server, so ignore public/hot
        // and always serve the built assets.
        Vite::useHotFile(storage_path('testing/vite.hot'));
    }
}
