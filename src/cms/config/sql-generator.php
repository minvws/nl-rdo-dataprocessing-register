<?php

declare(strict_types=1);

return [
    'filesystem_disk' => 'sql-generation',
    'filesystem_management_disk' => 'sql-generation-management',
    'migration_admin_log_start' => env('MIGRATION_ADMIN_LOG_START', '2024-01-11 13:06:01'),
];
