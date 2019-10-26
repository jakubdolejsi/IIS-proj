<?php


namespace Authentification;


abstract class PostDataValidator
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

	protected function getPostDataAndValidate(): array
	{
		return (array_map([$this, 'parseInput'], $_POST));
	}

	private function parseInput($data): string
	{
		return (htmlspecialchars(stripslashes(trim($data))));
	}

}