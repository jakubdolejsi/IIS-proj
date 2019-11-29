<?php


namespace Database;

use Enviroment\Enviroment;
use PDO;
use PDOStatement;


/**
 * Class Db
 * @package Database
 */
final class Db extends PDO
{

	public function __construct()
	{
		$default_options = [
			PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
			PDO::ATTR_EMULATE_PREPARES   => FALSE,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
		];
		$options = array_merge($default_options, Enviroment::DB_OPTIONS['OPTIONS']);

		parent::__construct(Enviroment::getDsn(), Enviroment::DB_OPTIONS['DB_USERNAME'],
			Enviroment::DB_OPTIONS['DB_PASSWORD'], Enviroment::DB_OPTIONS['OPTIONS']);

	}


	/**
	 * @param string $sql
	 * @param null   $args
	 * @return bool|PDOStatement
	 */
	public function run(string $sql, $args = NULL)
	{
		$stmt = $this->prepare($sql);
		$stmt->execute($this->toArray($this->nullCheck($args)));

		return $stmt;
	}

	/**
	 * @param $args
	 * @return array
	 */
	private function toArray($args): array
	{
		return (is_string($args)) ? [$args] : $args;
	}

	/**
	 * @param $args
	 * @return array
	 */
	private function nullCheck($args)
	{
		return $args ?? [];
	}

}