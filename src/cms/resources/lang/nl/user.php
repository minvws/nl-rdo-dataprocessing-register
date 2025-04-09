<?php

declare(strict_types=1);

return [
    'model_singular' => 'Gebruiker',
    'model_plural' => 'Gebruikers',
    'table_empty_heading' => 'Geen gebruikers',
    'role' => 'Rol',
    'global_roles' => 'Globale rollen',

    'organisation_roles' => 'Organisatie rollen',
    'organisation_roles_add' => 'Organisatie rol toevoegen',
    'organisation_attach' => 'Koppel organisaties',
    'organisation_attach_description' => 'Zoek de organisatie door (een deel van) de naam op te geven. Reeds gekoppelde organisations worden niet opgenomen in de resultaten.',
    'organisation_role_attach' => 'Gebruiker toevoegen aan organisatie',
    'organisation_role_attach_exists' => 'Deze gebruiker is al toegevoegd aan deze organisatie',
    'organisation_role_attach_description' => 'U gaat deze gebruiker verwijderen uit deze organisatie: weet u het zeker?',
    'organisation_role_detach' => 'Gebruiker verwijderen',
    'organisation_role_detach_description' => 'U gaat deze gebruiker verwijderen uit deze organisatie: weet u het zeker?',

    'name' => 'Naam',
    'password' => 'Wachtwoord',
    'email' => 'E-mail',

    'one_time_password' => [
        'code' => 'Code',
        'disable' => '2FA resetten',
        'disabled' => '2FA is gereset',
        'heading' => 'Tweefactorauthenticatie',
        'description' => 'Bevestig de toegang tot je account door de authenticatiecode in te voeren die door je authenticatietoepassing is verstrekt. Indien u niet beschikt over authenticatiecodes, neem contact op met de privacy officer of beheer.',
        'back_to_login_link' => 'Terug naar Inloggen',
    ],

    'profile' => [
        'my_profile' => 'Mijn profiel',

        'personal_info' => [
            'heading' => 'Persoonlijke informatie',
            'subheading' => 'Beheer je persoonlijke informatie.',
            'submit' => 'Opslaan',
            'notify' => 'Persoonlijke informatie bijgewerkt!',
        ],

        'one_time_password' => [
            'title' => 'Tweefactorauthenticatie',
            'description' => 'Beheer tweefactorauthenticatie voor je account',
            'code' => 'Code',
            'must_enable' => 'Tweefactorauthenticatie is verplicht',
            'actions' => [
                'enable' => 'Inschakelen',
                'disable' => 'Resetten',
                'confirm_finish' => 'Bevestigen',
                'cancel_setup' => 'Annuleren',
            ],
            'setup_key' => 'Sleutel: ',
            'enabled' => [
                'title' => 'Je hebt tweefactorauthenticatie ingeschakeld!',
                'description' => 'Tweefactorauthenticatie is nu ingeschakeld. Dat bevordert de veiligheid van je account.',
                'notify' => 'Tweefactorauthenticatie ingeschakeld',
            ],
            'disabling' => [
                'notify' => 'Tweefactorauthenticatie gereset',
            ],
            'finish_enabling' => [
                'title' => 'Voltooi het inschakelen van tweefactorauthenticatie.',
                'description' => 'Om het inschakelen van tweefactorauthenticatie te voltooien, scan je de volgende QR-code met behulp van de authenticatietoepassing van je telefoon of vul je handmatig de sleutel in.',
            ],
            'not_enabled' => [
                'title' => 'Je hebt tweefactorauthenticatie niet ingeschakeld.',
                'description' => 'Wanneer tweefactorauthenticatie is ingeschakeld, word je tijdens de authenticatie om een veilige, willekeurige token gevraagd. Je kunt deze token ophalen uit bijvoorbeeld de Google Authenticator-app van je telefoon.',
            ],
            'regenerate_codes' => [
                'action' => 'Codes opnieuw genereren',
                'notify' => 'De codes zijn opnieuw gegenereerd en opgeslagen',
            ],
            'confirmation' => [
                'success_notification' => 'Code geverifieerd. Tweefactorauthenticatie ingeschakeld.',
                'invalid_code' => 'De code die je hebt ingevoerd is ongeldig.',
                'too_many_requests' => 'Te veel inlogpogingen, probeer het opnieuw over :seconds seconden.',
            ],
        ],
    ],
];
