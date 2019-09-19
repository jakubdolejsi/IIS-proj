<?php


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

	const ERROR_DEBUG = 1;

	const DEVEL = 2;

	const PRODUCTION = 3;

	public static function getDsn()
	{
		$host = self::DB_OPTIONS['DB_HOST'];
		$dbName = self::DB_OPTIONS['DB_NAME'];
		$dsn = "mysql:host={$host};dbname={$dbName}";
		return $dsn;
	}
	public static function setErrorNotification()
	{
		if ($GLOBALS['VERSION'] == self::DEVEL) {
			ini_set('display_errors', 1);
			ini_set('display_startup_errors', 1);
			error_reporting(E_ALL);
		}
	}
	public static function setSession()
	{
		session_start();
	}
	public static function setEncoding()
	{
		mb_internal_encoding("UTF-8");
	}
}