<?php


namespace Controllers;


class HomeController extends aController
{

	protected function process($params)
	{
		$this->view = 'home';
	}

}