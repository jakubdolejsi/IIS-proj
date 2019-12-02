<?php /** @noinspection ALL */


namespace Enviroment;

class Enviroment
{
	const DB_OPTIONS = [
		'DB_HOST'     => 'localhost',
		'DB_USERNAME' => 'root',
		'DB_PASSWORD' => '',
		'DB_NAME'     => 'theatre',
		'OPTIONS'     => [],
	];

	//	const DEVEL = 1;
	//
	//	const PRODUCTION = 3;
	//
	const VERSION = [
		'DEVEL'      => 1,
		'PRODUCTION' => 3,
	];

	public static function getDsn()
	{
		$host = self::DB_OPTIONS['DB_HOST'];
		$dbName = self::DB_OPTIONS['DB_NAME'];

		return "mysql:host={$host};dbname={$dbName};charset=utf8";
	}

	public static function setEncoding()
	{
		mb_internal_encoding('UTF-8');
	}

	public static function setErrorNotification()
	{
		if ($GLOBALS['VERSION'] == self::VERSION['DEVEL']) {
			self::development();
		}
	}

	public static function setSessions()
	{
		if (!isset($_SESSION)) {
			session_start();
		}

		//Odhlaseni po vice nez 30 minutach neaktivity
		if (isset($_SESSION['lastActive']) && (time() - $_SESSION['lastActive'] > 1800)) {
			session_unset();
			session_destroy();
			session_start();
		}
		$_SESSION['lastActive'] = time();

		if (!isset($_SESSION['role'])) {
			$_SESSION['role'] = 'notRegisteredUser';
		}
	}

	public static function setVersion($version)
	{
		$GLOBALS['VERSION'] = $version;
	}

	private static function development()
	{
		$date = str_replace(':', '_', date('Y:m:d'));
		$dir = str_replace(__NAMESPACE__, '', __DIR__);
		$dir = $dir . 'Log' . DIRECTORY_SEPARATOR . $date;

		ini_set('display_startup_errors', 1);
		ini_set('display_errors', 1);
		ini_set('log_errors', 1);
		ini_set('error_log', $dir);
		error_reporting(E_ALL);
	}
}