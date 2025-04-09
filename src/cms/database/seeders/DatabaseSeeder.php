<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Config\Config;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        if (Config::string('app.env') === 'local') {
            $this->call([
                TestDataSeeder::class,
            ]);
        }
    }
}
