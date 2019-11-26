<?php


namespace Controllers;


class AboutController extends BaseController
{

	/**
	 * @param $params
	 * @return mixed
	 */
	public function process(array $params): void
	{
		$this->loadView('about');
	}
}