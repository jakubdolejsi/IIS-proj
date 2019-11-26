<?php


namespace Controllers;


class PaymentController extends BaseController
{

	public function actionDefault(): void
	{
		$this->loadView('payment');
	}
}