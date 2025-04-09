
# Hosting changelog

Dit bestand bevat wijzigingen waarmee rekening moet worden gehouden
bij het uitrollen van een release op de hostingomgeving. Dit kunnen
nieuwe omgevingsvariabelen zijn, actieve scripts enz.

## Initiele stappen:

-   verwerk migraties in `database/sql` om de database-tabellen te maken
-   maak een admin-user: `php artisan make:admin-user` & deel dit wachtwoord met Jeroen Vloothuis & Tijn Schmits
-   maak de gedeelde storage-mappen aan die gedeeld zal worden bij alle releases, stel het pad in via `FILESYSTEM_SHARED_STORAGE_PATH`    

### Publieke website

De publieke website zijn statische HTML paginas die gegenereerd worden
op basis van data uit het CMS. Om dit te kunnen genereren zijn er de
volgende tools nodig:

| tool              | versie  |                                      installatie |                                                   bestanden |
| ----------------- | :-----: | -----------------------------------------------: | ----------------------------------------------------------: |
| Hugo **extended** | 0.121.1 | [install guide](https://gohugo.io/installation/) |  [release files](https://github.com/gohugoio/hugo/releases) |
| Dart sass         | 1.69.5  |  [install guide](https://sass-lang.com/install/) | [release files](https://github.com/sass/dart-sass/releases) |

Er zal periodiek een build plaatsvinden die alle HTML bestanden update
met de laatste gegevens. Dit wordt gedaan door middel van de queue en
daarvoor is er ook een worker nodig. Deze zal luisteren naar de queue
met het `php artisan queue:work` commando.

### cron
Daarnaast is er een cron-task nodig, zodat we taken kunnen inplannen.
De cronjob moet elke minuut het commando `php artisan schedule:run` starten.
Voor meer info, zie https://laravel.com/docs/10.x/scheduling#running-the-scheduler

### storage
Op de **testomgeving** is het handig om de output van de publieke
website ook te kunnen inzien. Dit kan gedaan worden door de storage
toegankelijk te maken met `php artisan storage:link`.

## Workers

Het verwerkingsregister moet gebruik maken van workers voor het uitvoeren van
jobs. Na iedere release zullen workers opnieuw opgestart moeten worden opdat
ze de code van de meest recente release uitvoeren.

## Changelog per Tag:

## DEVELOP

- Na deployment moeten de bestaande caches verwijderd worden: `php artisan optimize:clear`
- Na de deployment moeten voor deze release alle worker processen opnieuw opgestart worden
- Na het herstarten van de worker moet eenmalig de publieke website opnieuw opgebouwd worden: `php artisan public-website:refresh`

## v1.7.1

- Na deployment moeten de bestaande caches verwijderd worden: `php artisan optimize:clear`
- Na de deployment moeten voor deze release alle worker processen opnieuw opgestart worden
- Na het herstarten van de worker moet eenmalig de publieke website opnieuw opgebouwd worden: `php artisan public-website:refresh`
- Via de env-var `PUBLIC_WEBSITE_CHECK_PROXY` is het mogelijk om de proxy in te stellen voor de check van de publieke website

## v1.7.0

- Na deployment moeten de bestaande caches verwijderd worden: `php artisan optimize:clear`
- Na de deployment moeten voor deze release alle worker processen opnieuw opgestart worden
- Na het herstarten van de worker moet eenmalig de publieke website opnieuw opgebouwd worden: `php artisan public-website:refresh`
- Via de env-var `PUBLIC_WEBSITE_CHECK_BASE_URL` is het mogelijk om de url voor de check van de publieke website op te geven

## v1.6.3

- Na deployment moeten de bestaande caches verwijderd worden: `php artisan optimize:clear`
- Na de deployment moeten voor deze release alle worker processen opnieuw opgestart worden
- Na het herstarten van de worker moet eenmalig de publieke website opnieuw opgebouwd worden: `php artisan public-website:refresh`

## v1.6.2

- Na deployment moeten de bestaande caches verwijderd worden: `php artisan optimize:clear`
- Na de deployment moeten voor deze release alle worker processen opnieuw opgestart worden
- Na het herstarten van de worker moet eenmalig de publieke website opnieuw opgebouwd worden: `php artisan public-website:refresh`

## v1.6.1

- Na deployment moeten de bestaande caches verwijderd worden: `php artisan optimize:clear`
- Na de deployment moeten voor deze release alle worker processen opnieuw opgestart worden
- Na het herstarten van de worker moet eenmalig de publieke website opnieuw opgebouwd worden: `php artisan public-website:refresh`

## v1.6.0

- Na deployment moeten de bestaande caches verwijderd worden: `php artisan optimize:clear`
- Na de deployment moeten voor deze release alle worker processen opnieuw opgestart worden
- Na het herstarten van de worker moet eenmalig de publieke website opnieuw opgebouwd worden: `php artisan public-website:refresh`
- Het commando om de queue te verwerken moet een extra parameter krijgen zodat we taken prioriteit kunnen geven, het nieuwe command is: `php artisan queue:work --queue=high,default,low` 

## v1.5.2

- Na deployment moeten de bestaande caches verwijderd worden: `php artisan optimize:clear`
- Na de deployment moeten voor deze release alle worker processen opnieuw opgestart worden
- Na het herstarten van de worker moet eenmalig de publieke website opnieuw opgebouwd worden: `php artisan public-website:refresh`

## v1.5.1

- Na deployment moeten de bestaande caches verwijderd worden: `php artisan optimize:clear`
- Na de deployment moeten voor deze release alle worker processen opnieuw opgestart worden
- Na het herstarten van de worker moet eenmalig de publieke website opnieuw opgebouwd worden: `php artisan public-website:refresh`

## v1.5.0

- Na deployment moeten de bestaande caches verwijderd worden: `php artisan optimize:clear`
- Na de deployment moeten voor deze release alle worker processen opnieuw opgestart worden
- Na het herstarten van de worker moet eenmalig de publieke website opnieuw opgebouwd worden: `php artisan public-website:refresh`

## v1.4.2

- Na deployment moeten de bestaande caches verwijderd worden: `php artisan optimize:clear`
- Na de deployment moeten voor deze release alle worker processen opnieuw opgestart worden
- Na het herstarten van de worker moet eenmalig de publieke website opnieuw opgebouwd worden: `php artisan public-website:refresh`

## v1.4.1

- Na deployment moeten de bestaande caches verwijderd worden: `php artisan optimize:clear`
- Na de deployment moeten voor deze release alle worker processen opnieuw opgestart worden
- Na het herstarten van de worker moet eenmalig de publieke website opnieuw opgebouwd worden: `php artisan public-website:refresh`

## v1.4.0

- Na deployment moeten de bestaande caches verwijderd worden: `php artisan optimize:clear`
- Na de deployment moeten voor deze release alle worker processen opnieuw opgestart worden
- Na het herstarten van de worker moet eenmalig de publieke website opnieuw opgebouwd worden: `php artisan public-website:refresh`

## v1.3.6

- Na deployment moeten de bestaande caches verwijderd worden: `php artisan optimize:clear`
- Na de deployment moeten voor deze release alle worker processen opnieuw opgestart worden
- Na het herstarten van de worker moet eenmalig de publieke website opnieuw opgebouwd worden: `php artisan public-website:refresh`

## v1.3.5

- Na deployment moeten de bestaande caches verwijderd worden: `php artisan optimize:clear`
- Na de deployment moeten voor deze release alle worker processen opnieuw opgestart worden
- Na het herstarten van de worker moet eenmalig de publieke website opnieuw opgebouwd worden: `php artisan public-website:refresh`

## v1.3.4

- Na deployment moeten de bestaande caches verwijderd worden: `php artisan optimize:clear`
- Na de deployment moeten voor deze release alle worker processen opnieuw opgestart worden
- Na het herstarten van de worker moet eenmalig de publieke website opnieuw opgebouwd worden: `php artisan public-website:refresh`

## v1.3.3

- Na deployment moeten de bestaande caches verwijderd worden: `php artisan optimize:clear`
- Na de deployment moeten voor deze release alle worker processen opnieuw opgestart worden
- Na het herstarten van de worker moet eenmalig de publieke website opnieuw opgebouwd worden: `php artisan public-website:refresh`

## v1.3.2

- Na de deployment moeten de bestaande caches verwijderd worden: `php artisan optimize:clear`
- Na de deployment moeten voor deze release alle worker processen opnieuw opgestart worden
- Na het herstarten van de worker moet eenmalig de publieke website opnieuw opgebouwd worden: `php artisan public-website:refresh`

## v1.3.1

- Na de deployment moeten voor deze release alle worker processen opnieuw opgestart worden
- Na het herstarten van de worker moet eenmalig de publieke website opnieuw opgebouwd worden: `php artisan public-website:refresh`

## v1.3.0

- Na de deployment moeten voor deze release alle worker processen opnieuw opgestart worden
- Na het herstarten van de worker moet eenmalig de publieke website opnieuw opgebouwd worden: `php artisan public-website:refresh`

## v1.2.0

- Na de deployment moeten voor deze release alle worker processen opnieuw opgestart worden
- Na het herstarten van de worker moet eenmalig de publieke website opnieuw opgebouwd worden: `php artisan public-website:refresh`
- Voer het commando uit om de bestaande avatars op te ruimen: `php artisan app:delete-organisation-avatars`

## v1.1.0

- Na de deployment moeten voor deze release alle worker processen opnieuw opgestart worden
- Na het herstarten van de worker moet eenmalig de publieke website opnieuw opgebouwd worden: `php artisan public-website:refresh`
- Na de release eenmalig het volgende command uitvoeren: `php artisan app:convert-entity-number`

## v1.0.17

- Na de deployment moeten voor deze release alle worker processen opnieuw opgestart worden
- Na het herstarten van de worker moet eenmalig de publieke website opnieuw opgebouwd worden: `php artisan public-website:refresh`
- Controleer of de cron-job ingesteld is (zie "cron" onder "initiele stappen")

## v1.0.16

- Na de deployment moeten voor deze release alle worker processen opnieuw opgestart worden
- Na het herstarten van de worker moet eenmalig de publieke website opnieuw opgebouwd worden: `php artisan public-website:refresh`

## v1.0.15

- We gaan de env-var `LARAVEL_STORAGE_PATH` niet gebruiken, deze mag dus verwijderd worden
- `FILESYSTEM_SHARED_STORAGE_PATH` instellen met het pad naar de "shared storage" (gedeeeld over de releases)
- Na de deployment moeten voor deze release alle worker processen opnieuw opgestart worden
- Na het herstarten van de worker moet eenmalig de publieke website opnieuw opgebouwd worden: `php artisan public-website:refresh`

## v1.0.14

- Initiele stap toegevoegd voor de storage-map, let op dat de locatie ook ingesteld is dmv de env-var `LARAVEL_STORAGE_PATH`
- Zorg ervoor dat de env-var `QUEUE_CONNECTION` ofwel verwijderd is, of de (standaard-waarde) `database` bevat
- Na de deployment moeten voor deze release alle worker processen opnieuw opgestart worden
- Na de deployment moeten de caches geleegd worden, zodat deze opnieuw opgebouwd kunnen worden voor de nieuwe versie: `php artisan optimize:clear`
- Na het herstarten van de worker moet eenmalig de publieke website opnieuw opgebouwd worden: `php artisan public-website:refresh`

## v1.0.13

- Na de deployment moeten voor deze release alle worker processen opnieuw opgestart worden.
- Na het herstarten van de worker moet eenmalig de publieke website opnieuw opgebouwd worden: `php artisan public-website:refresh`

## v1.0.12

- Na de deployment moeten voor deze release alle worker processen opnieuw opgestart worden.
- Na het herstarten van de worker moet eenmalig de publieke website opnieuw opgebouwd worden: `php artisan public-website:refresh`

## v1.0.11

- Na de deployment moeten voor deze release alle worker processen opnieuw opgestart worden.
- Na het herstarten van de worker moet eenmalig de publieke website opnieuw opgebouwd worden: `php artisan public-website:refresh`
- Deze versie bevat geen sql-files, de database blijft dus op versie v1.0.10

## v1.0.10

- Na de deployment moeten voor deze release alle worker processen opnieuw opgestart worden.

## v1.0.9

- Na de deployment moeten voor deze release alle worker processen opnieuw opgestart worden.

## v1.0.8

- Na de deployment moeten voor deze release alle worker processen opnieuw opgestart worden.

## v1.0.7

- Tbv het versturen van mail via smtp moeten de volgende env-vars gezet worden:
  - `OUTBOX_SMTP_HOST`
  - `OUTBOX_SMTP_PORT` (optional, defaults to 1025)
  - `OUTBOX_SMTP_USERNAME`
  - `OUTBOX_SMTP_PASSWORD`
  - `OUTBOX_SMTP_ENCRYPTION` (optional, defaults to `tls`)
  - `OUTBOX_SMTP_FROM`

- `PUBLIC_WEBSITE_BASE_URL` kan nu ook een relatief path worden, zoals `/public/subfolder`. Notes:
  - Indien je de website op root wil hosten, dan dient deze environment variabele de waarde `"."` te hebben.
  - (Dit is ongewijzigd:) deze URL mag geen trailing slash hebben.

- Voor de scanning van documenten is clamav nodig, evt is de locatie van de socket aan te passen dmv de env-var
  `VIRUSSCANNER_SOCKET` (standaard staat deze ingesteld op `unix:///var/run/clamav/clamd.ctl`). Voor meer info over de socket-
  configuratie, zie: https://github.com/clue/socket-raw?tab=readme-ov-file#createclient

- Na de deployment moeten voor deze release alle worker processen opnieuw opgestart worden.

- De huidige gebruikers in de database hebben allen geen enkele rol toegekend en hebben dus ook geen rechten. Er zal een
  nieuwe admin gebruiker aangemaakt moeten worden met het `php artisan make:admin-user` commando. Deze gebruiker heeft
  alle rollen (en dus rechten), en kan vervolgens alle andere gebruikers de juiste rollen toekennen.

## v1.0.6

- REQUIREMENT:
    - exiftool moet beschikbaar zijn op de server

- Na de deployment moeten voor deze release alle worker processen opnieuw opgestart worden.

## v1.0.5
- controleer of minimaal de volgende env-vars op de omgeving
  beschikbaar (en de juiste waarde bevatten). Een deel van deze zijn
  al correct ingesteld. Voor de volledigheid is hieronder de hele
  lijst opgenomen:
  - `APP_KEY`, unieke application key (kan gezet worden via `php
    artisan key:generate`)
  - `APP_URL`, url waarop de admin-interface beschikbaar is. Dit dient
    de volledige URL te zijn inclusief het `https` deel.
  - `DB_HOST`
  - `DB_DATABASE`
  - `DB_USERNAME`
  - `DB_PASSWORD`
  - `PUBLIC_WEBSITE_BUILD_AFTER_HOOK`, het commando / script wat
    uitegevoerd moet worden na het bouwen van de statische site.
    Gemaakt voor mogelijkheid (productie) om de site naar een andere
    machine te kopieren.
  - `FILESYSTEM_PUBLIC_WEBSITE_ROOT`, het pad waarin de statische
    website gebouwd wordt.
  - `PUBLIC_WEBSITE_BASE_URL`, url waarop de publieke (gegenereerde,
    statische) website beschikbaar zal zijn. Dit dient de volledige
    base URL met schema te zijn, dus iets als: `https://example.com`.
    Deze URL mag geen trailing slash hebben.
- de worker is een proces wat constant moet draaien. Dit proces pakt
  automatisch taken op van de queue (database). De worker kan in een
  Systemd service gezet worden. Deze heeft alle environment variabelen
  nodig die de admin-applicatie ook heeft. De worker kan gestart
  worden via `php artisan queue:work`.
- de admin-applicatie zal bij bepaalde acties in het systeem de
  statische website her-genereren. De resulterende HTML etc. bestanden
  worden neergezet in een directory die in te stellen is via
  `FILESYSTEM_PUBLIC_WEBSITE_ROOT`. Standaard staat deze instelling op
  `./storage/app/`.
  - de worker maakt Markdown bestanden & afbeeldingen, de sub-map (van
    `FILESYSTEM_PUBLIC_WEBSITE_ROOT`) waarin deze bestanden komen is:
    `public-website/content`. Deze moet schrijfbaar zijn voor de
    worker.
  - vervolgens worden m.b.v. Hugo deze markdown-bestanden omgezet naar
    HTML. De uitvoer hiervan komt in: `public/public-website`
    (relatief ten opzichte van `FILESYSTEM_PUBLIC_WEBSITE_ROOT`). Deze
    map dient ook schrijfbaar te zijn. Let op: omdat de website
    volledig statisch is, zullen hierin volledige URL's naar de
    verschillende subpagina's zitten. De basis URL die gebruikt wordt
    kan via de `PUBLIC_WEBSITE_BASE_URL` environment variabele
    ingesteld worden.
  - de applicatie heeft een "hook" om het mogelijk te maken om,
    bijvoorbeeld via rsync, de statische bestanden naar een andere
    server te uploaden. Deze kan ingesteld worden via de
    `PUBLIC_WEBSITE_BUILD_AFTER_HOOK` environment variabele. De worker
    zal het commando wat hier in staat uitvoeren. Dit dient een
    programma of script te zijn zonder parameters. Mochten er
    parameters nodig zijn dan kan dit gedaan worden door een shell
    script te maken en dat hier in te stellen.

## v1.0.4

- Werk de environment variabelen bij. Zie `docs/environment_variables.md` voor
  een overzicht van alle variabelen.
- Installeer Hugo **extended** (zie "Publieke website" hierboven).
- Installeer Dart sass (zie "Publieke website" hierboven).
- Stel (eventueel) de environment variabelen in voor de plek van de publieke
  website bestanden via `FILESYSTEM_PUBLIC_WEBSITE_ROOT` (zie
  `docs/environment_variables.md`)
- Draai eenmalig `php artisan storage:link` op de Test server. Dit maakt de
  benodigde directories voor de opslag van bestanden. De configuratie van de
  paden kan via de environment variabelen gedaan worden.
- Start een worker welke het `php artisan queue:work` draait (met dit commando
  luistert de worker naar de queue). De worker heeft de zelfde environment
  variabelen nodig als de hoofdapplicatie.

## v1.0.1

-   gooi alle database-tabellen weg
-   execute alle SQL files in de folder `database/sql/v1.0.1` om alle tabellen weer aan te maken
-   maak admin-user aan met het `php artisan make:admin-user`-script

## v0.0.7

nieuw migratie-script toegevoegd: `database/sql/2023_12_05_152513_wpg.sql`
