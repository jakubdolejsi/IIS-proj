<?php


namespace Controllers;


abstract class aView
{

	protected $view;

	public $controller;

	public function __construct($template, $controller)
	{
		$this->view = $template;
		$this->controller = $controller;
	}
}