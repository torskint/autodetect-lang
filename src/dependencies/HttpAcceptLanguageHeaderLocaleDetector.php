<?php

namespace Torskint\AutodetectLang\Dependencies;

class HttpAcceptLanguageHeaderLocaleDetector
{
	const HTTP_ACCEPT_LANGUAGE_HEADER_KEY = 'HTTP_ACCEPT_LANGUAGE';

	public static function detect()
	{
		$httpAcceptLanguageHeader = self::getHttpAcceptLanguageHeader();

		# Si HTTP_ACCEPT_LANGUAGE n'est pas disponible
		if ( empty($httpAcceptLanguageHeader) ) {
			return [];
		}

		$locales = self::getWeightedLocales($httpAcceptLanguageHeader);
		$sortedLocales = self::sortLocalesByWeight($locales);

		return array_column($sortedLocales, 'locale');
	}


	private static function getHttpAcceptLanguageHeader()
	{
		if ( !empty($_SERVER[self::HTTP_ACCEPT_LANGUAGE_HEADER_KEY]) ) {
			return trim($_SERVER[self::HTTP_ACCEPT_LANGUAGE_HEADER_KEY]);
		}
		return false;
	}

	private static function getWeightedLocales($httpAcceptLanguageHeader)
	{
		$weightedLocales = [];

		// We break up the string 'en-CA,ar-EG;q=0.5' along the commas,
		// and iterate over the resulting array of individual locales. Once
		// we're done, $weightedLocales should look like
		// [['locale' => 'en-CA', 'q' => 1.0], ['locale' => 'ar-EG', 'q' => 0.5]]
		foreach (explode(',', $httpAcceptLanguageHeader) as $locale) {
			// separate the locale key ("ar-EG") from its weight ("q=0.5")
			$localeParts = explode(';', $locale);

			$weightedLocale = ['locale' => $localeParts[0]];

			if (count($localeParts) == 2) {
				// explicit weight e.g. 'q=0.5'
				$weightParts = explode('=', $localeParts[1]);

				// grab the '0.5' bit and parse it to a float
				$weightedLocale['q'] = floatval($weightParts[1]);
			} else {
				// no weight given in string, ie. implicit weight of 'q=1.0'
				$weightedLocale['q'] = 1.0;
			}

			$weightedLocales[] = $weightedLocale;
		}

		return $weightedLocales;
	}

	/**
	 * Sort by high to low `q` value
	 */
	private static function sortLocalesByWeight($locales)
	{
		usort($locales, function ($a, $b) {
			// usort will cast float values that we return here into integers,
			// which can mess up our sorting. So instead of subtracting the `q`,
			// values and returning the difference, we compare the `q` values and
			// explicitly return integer values.
			if ($a['q'] == $b['q']) {
				return 0;
			}

			if ($a['q'] > $b['q']) {
				return -1;
			}

			return 1;
		});

		return $locales;
	}

}