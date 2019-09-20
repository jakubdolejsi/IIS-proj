<?php


namespace Database;

use Enviroment\Enviroment;
use PDO;
use PDOStatement;


/**
 * Class Db
 * @package Database
 */
class Db extends PDO implements IDatabase
{

	public function __construct()
	{
		$default_options = [
			PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
			PDO::ATTR_EMULATE_PREPARES   => FALSE,
		];
		$options = array_merge($default_options, Enviroment::DB_OPTIONS['OPTIONS']);

		parent::__construct(Enviroment::getDsn(), Enviroment::DB_OPTIONS['DB_USERNAME'],
			Enviroment::DB_OPTIONS['DB_PASSWORD'], Enviroment::DB_OPTIONS['OPTIONS']);

	}


	/**
	 * @param string     $sql
	 * @param array|NULL $args
	 * @return bool|PDOStatement
	 */
	public function run(string $sql, array $args = NULL)
	{
		$stmt = $this->prepare($sql);
		$stmt->execute($this->toArray($args));

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

}