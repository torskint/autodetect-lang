```php

require 'vendor/autoload.php';

use Torskint\AutodetectLang\Detector;

Appelle HttpAcceptLanguageLocales & ensuite IpAddressLocales
En gros il fourni une liste complete de ces deux fonctions (Sans doublons)
var_dump( Detector::detect() );


var_dump( Detector::HttpAcceptLanguageLocales() );
var_dump( Detector::IpAddressLocales() );

```