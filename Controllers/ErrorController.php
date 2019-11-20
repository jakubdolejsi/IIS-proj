<?php


namespace Controllers;


class ErrorController extends baseController
{

	/**
	 * @param array $params
	 */
	public function process(array $params): void
	{
		$this->loadView('error404');
		header('HTTP/1.0 404 Not Found');
	}
}