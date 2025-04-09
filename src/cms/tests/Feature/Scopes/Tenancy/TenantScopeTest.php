<?php

declare(strict_types=1);

namespace Tests\Feature\Scopes\Tenancy;

use App\Models\Organisation;
use App\Models\Scopes\TenantScope;
use App\Models\Wpg\WpgProcessingRecord;
use Filament\Facades\Filament;
use Tests\Feature\FeatureTestCase;

class TenantScopeTest extends FeatureTestCase
{
    public function testItScopes(): void
    {
        $organisation = Organisation::factory()
            ->hasUsers(1)
            ->create();

        $user = $organisation->users->first();
        $this->actingAs($user);
        Filament::setTenant($organisation);

        $organisation2 = Organisation::factory()->create();
        WpgProcessingRecord::factory()->for($organisation, 'organisation')->create();
        WpgProcessingRecord::factory()->for($organisation2, 'organisation')->create();
        WpgProcessingRecord::addGlobalScope(new TenantScope());

        $this->assertCount(1, WpgProcessingRecord::all());
        $this->assertGreaterThan(1, WpgProcessingRecord::withoutGlobalScope(TenantScope::class)->count());
    }
}
