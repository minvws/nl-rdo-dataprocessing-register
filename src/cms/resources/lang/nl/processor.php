<?php

declare(strict_types=1);

return [
    'model_singular' => 'Verwerker',
    'model_plural' => 'Verwerkers',
    'table_empty_heading' => 'Geen verwerkers',

    'name' => 'Naam organisatie',
    'email' => 'E-mail',
    'phone' => 'Telefoonnummer',

    'measures' => 'Maatregelen',
    'measures_implemented' => 'Vastgesteld beveiligingsbeleid dat ook is geïmplementeerd',

    'other_measures' => 'Overige maatregelen',
    'measures_description' => 'Toelichting maatregelen',
    'security_access_options' => [
        'Personeel dat onder leiding van de verantwoordelijke staat',
        'Personeel dat onder leiding van de verwerker staat',
        'Derden',
    ],
    'security_options' => [
        'Nee',
        'Ja, via een eigen netwerk',
        'Ja, via een publiek netwerk',
    ],
];
