<?php


namespace Authentication;


abstract class Password extends Validator
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


}