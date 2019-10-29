<?php


namespace Authentication;


abstract class Validator
{
	/**
	 * @param $password
	 * @return false|string
	 */
	protected function hashPassword($password)
	{
		return password_hash($password, PASSWORD_DEFAULT);
	}

	/**
	 * @param $password
	 * @param $hash
	 * @return bool
	 */
	protected function verifyHashPassword($password, $hash): bool
	{
		return password_verify($password, $hash);
	}

	// TODO mohl by rovnou vracet model uzivatele
	protected function getPostDataAndValidate(): array
	{
		return (array_map([$this, 'parseInput'], $_POST));
	}

	private function parseInput($data): string
	{
		return (htmlspecialchars(stripslashes(trim($data))));
	}

}