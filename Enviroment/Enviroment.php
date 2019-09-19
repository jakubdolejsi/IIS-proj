<?php /** @noinspection ALL */


namespace Enviroment;


class Enviroment
{
	const DB_OPTIONS = [
		'DB_HOST'     => 'localhost',
		'DB_USERNAME' => 'root',
		'DB_PASSWORD' => '',
		'DB_NAME'     => 'hotel',
		'OPTIONS'     => [],
	];

	const DEVEL = 1;

	const PRODUCTION = 3;

	public static function getDsn()
	{
		$host = self::DB_OPTIONS['DB_HOST'];
		$dbName = self::DB_OPTIONS['DB_NAME'];

		return "mysql:host={$host};dbname={$dbName}";
	}
	public static function setErrorNotification()
	{
		if ($GLOBALS['VERSION'] == self::DEVEL) {
			self::development();
		}
	}
	public static function setSession()
	{
		session_start();
	}
	public static function setEncoding()
	{
		mb_internal_encoding('UTF-8');
	}

	private static function development()
	{
		$date = str_replace(':','_',date('Y:m:d'));
		$dir = str_replace(__NAMESPACE__, '', __DIR__);
		$dir = $dir . 'Log' . DIRECTORY_SEPARATOR . $date;

		ini_set('display_startup_errors', 1);
		ini_set('display_errors', 1);
		ini_set('log_errors', 1);
		ini_set('error_log',$dir);
		error_reporting(E_ALL);

	}
}