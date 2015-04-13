<?php

	/**
	 * Sistemin Konfigirasyon Bilgileri config.inc.php den okunup bu s�n�fa g�nderilir. T�m Conf Sistemi bu static s�n�f �zerinde y�netilir.
	 * @useful $config::get('db/host')
	 * @TODO Singleton eklenecek
	 */

	namespace Config;

	class Config
	{

		private static $configData;

		public static function readConfig ()
		{
			$configFile ="config.ini";
			self::$configData = parse_ini_file($configFile,true);

		}


		public static function setConfig ($_config = null)
		{
			self::$configData = $_config;
		}

		public static function get ($_req = null)
		{
			if ($_req !== null) {
				$config = self::$configData;
				$request   = explode ('/',$_req);
				foreach ($request as $bit) {
					if (isset( $config[$bit] )) {
						$config = $config[$bit];
					}
				}
				return $config;
			}
			return false;
		}
	}


