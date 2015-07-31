# Låtsasbörsen

## Installation
1. `npm install`
2. `bower install`
3. `gulp`
4. `php artisan migrate`
5. Besök `*.dev/register/` och skapa ett nytt konto

## Konfiguration

- Skapa ny fil: `config/nordnet.php`
- Fyll den enligt nedan:
```
<?php
  return [
      'username' => 'USERNAME',
      'password' => 'PASSWORD'
  ];
```
- Användarnamn och lösenord till nEXT API skapar du här: https://api.test.nordnet.se/account/register

## Flöde

1. En USER hittar, på en MARKET, ett INSTRUMENT denne vill köpa
2. Om USER's PORTFOLIO innehåller tillräckligt mycket pengar kan USER lägga en ORDER på INSTRUMENT:et av ordertypen köp.
3. När ORDER är lagd söker systemet efter en annan ORDER av motsatt ordertyp.
4. Om priserna matchas registreras en transaktion och vår USER får en POSITION i utbyte mot sitt kapital. (Eller vice versa om ordertypen var sälj).
5. I databasen sätts INSTRUMENT's kvota-justering till negativt antalet köpta andelar av INSTRUMENT.
6. När USER sedan vill sälja sin POSITION lägger denne ut en sälj-ORDER.
7. Systemet söker efter en annan ORDER av köp-typ, och om priserna matchas registreras en transaktion och vår USER blir av med sin POSITION i utbyte mot kapital.
8. I databasen återställs INSTRUMENT's kvota-justering till 0.
