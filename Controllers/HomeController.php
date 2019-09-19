<?php


namespace Controllers;


class HomeController extends aController
{
	protected function process($params)
	{
		$this->view->loadControllerView('home');
		$this->data['tuska'] = "mojekurvaData";
	}

}