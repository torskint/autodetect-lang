<?php

require '../vendor/autoload.php';

use Torskint\AutodetectLang\Detector;

var_dump( Detector::detect() );
var_dump( Detector::HttpAcceptLanguageLocales() );
var_dump( Detector::IpAddressLocales() );
