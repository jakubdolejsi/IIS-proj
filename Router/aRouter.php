<?php


namespace Router;


abstract class aRouter
{
	protected $controller;

	abstract protected function process($params);

	protected function redirect($url)
	{
		header("Location: /$url");
		header("Connection: close");
		exit;
	}
}