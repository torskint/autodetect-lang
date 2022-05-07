<?php

namespace Torskint\AutodetectLang\Dependencies;

use GeoIp2\Database\Reader;
use Torskint\AutodetectLang\Dependencies\Country2Locale;
use GeoIp2\Exception\AddressNotFoundException;

class IpAddressLocaleDetector
{
	/**
	 * https://www.maxmind.com/en/accounts/399520/geoip/downloads ( GeoLite2 Country )
	 */
	const MAX_MIND_DB_FILEPATH = __DIR__ . '/config/GeoLite2-Country.mmdb';

	private static $maxMindDbReader;

	public static function detect()
	{
		$ipAddress = self::getIpAddress();

		try {
			$record = self::getMaxMindDbReader()->country($ipAddress);

			$locales = Country2Locale::done($record->country->isoCode);
			$normalizedLocales = str_replace('_', '-', $locales);

			# 'en_GB,ga_GB,cy_GB,gd_GB,kw_GB'
			return explode(',', $normalizedLocales);
		} catch (AddressNotFoundException $ex) {
			return [];
		}
	}

	private static function getIpAddress()
	{
		$ipaddress = '';
		if (isset($_SERVER['HTTP_CLIENT_IP'])) {
			$ipaddress = $_SERVER['HTTP_CLIENT_IP'];
		} else if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else if(isset($_SERVER['HTTP_X_FORWARDED'])) {
			$ipaddress = $_SERVER['HTTP_X_FORWARDED'];
		} else if(isset($_SERVER['HTTP_X_CLUSTER_CLIENT_IP'])) {
			$ipaddress = $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
		} else if(isset($_SERVER['HTTP_FORWARDED_FOR'])) {
			$ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
		} else if(isset($_SERVER['HTTP_FORWARDED'])) {
			$ipaddress = $_SERVER['HTTP_FORWARDED'];
		} else if(isset($_SERVER['REMOTE_ADDR'])) {
			$ipaddress = $_SERVER['REMOTE_ADDR'];
		} else {
			$ipaddress = 'UNKNOWN';
		}

		return $ipaddress;
	}

	private static function getMaxMindDbReader()
	{
		if (self::$maxMindDbReader == null) {
			self::$maxMindDbReader = new Reader(self::MAX_MIND_DB_FILEPATH);
		}

		return self::$maxMindDbReader;
	}
}