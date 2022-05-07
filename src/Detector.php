<?php

namespace Torskint\AutodetectLang;

use Torskint\AutodetectLang\Dependencies\IpAddressLocaleDetector;
use Torskint\AutodetectLang\Dependencies\HttpAcceptLanguageHeaderLocaleDetector;

/**
 * Toutes les autres classes en une
 */
class Detector
{
	
	public static function detect()
	{
		$locales = HttpAcceptLanguageHeaderLocaleDetector::detect();
		if ( empty($locales) ) {
			$locales = IpAddressLocaleDetector::detect();
		}

		return self::clearDuplicata($locales);
	}

	public static function HttpAcceptLanguageLocales()
	{
		return HttpAcceptLanguageHeaderLocaleDetector::detect();
	}

	public static function IpAddressLocales()
	{
		return IpAddressLocaleDetector::detect();
	}

	private static function clearDuplicata($locales)
	{
		return array_unique(array_map(function ($locale) {
			
			$explode = explode('-', $locale);
			$isoCode = strtolower( $explode[0] );

			return $isoCode;

		}, $locales));
	}
}