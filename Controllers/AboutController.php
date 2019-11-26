<?php


namespace Controllers;


class AboutController extends BaseController
{

	public function actionDefault(): void
	{
		$this->loadView('about');
	}
}