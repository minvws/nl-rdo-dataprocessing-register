<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Table Header
    |--------------------------------------------------------------------------
    */

    'table.heading' => 'Audits',

    /*
    |--------------------------------------------------------------------------
    | Table Columns
    |--------------------------------------------------------------------------
    */

    'column.user_name' => 'Gebruiker',
    'column.event' => 'Bewerking',
    'column.created_at' => 'Aangemaakt',
    'column.old_values' => 'Oude waardes',
    'column.new_values' => 'Nieuwe waardes',

    /*
    |--------------------------------------------------------------------------
    | Table Actions
    |--------------------------------------------------------------------------
    */

    'action.restore' => 'Terugzetten',

    /*
    |--------------------------------------------------------------------------
    | Notifications
    |--------------------------------------------------------------------------
    */

    'notification.restored' => 'Audit teruggezet',
    'notification.unchanged' => 'Geen wijzigingen',

];
