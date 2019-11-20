<?php


namespace Exceptions;

use Exception;


/**
 * Class baseException
 * @package Exceptions
 */
class BaseException extends Exception
{

	public function errorMessage(string $inputMsg = ''): string
	{
		$msg = 'Error on line ' . $this->getLine() . ' in ' . $this->getFile() . ' : <br>' .
			$this->getMessage() . '<br>' . $inputMsg . '<br>' . ' Backtrace: <pre>' . print_r
			($this->getTraceAsString(),
				TRUE) . '<pre>';

		return $msg;
	}
}