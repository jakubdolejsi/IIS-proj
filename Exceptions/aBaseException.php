<?php


namespace Exceptions;

use Exception;


/**
 * Class aBaseException
 * @package Exceptions
 */
class aBaseException extends Exception
{

	public function errorMessage(string $inputMsg = ''): string
	{
		$msg = 'Error on line ' . $this->getLine() . ' in ' . $this->getFile() . ' : <br>' .
			$this->getMessage() . '<br>' . $inputMsg;

		return $msg;
	}
}