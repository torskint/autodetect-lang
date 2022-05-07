```php

require 'vendor/autoload.php';

use Torskint\AutodetectLang\Detector;

## La fonction detect
## Appelle HttpAcceptLanguageLocales & ensuite IpAddressLocales
## En gros il fourni une liste complete de ces deux fonctions (Sans doublons)
var_dump( Detector::detect() );

## HttpAcceptLanguageLocales
## Détecte les langues suivant les préférences de l'utilisateur ( Via le navigateur web )
var_dump( Detector::HttpAcceptLanguageLocales() );

## IpAddressLocales
## Détecte les langues via l'adresse IP de l'utilisateur
var_dump( Detector::IpAddressLocales() );

```