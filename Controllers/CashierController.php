<?php


namespace Controllers;


use Exceptions\InvalidRequestException;


class CashierController extends BaseController
{
    public function actionAdd($params):void
    {
    	$this->hasPermission('cashier', 'admin');
        $search = $this->getModelFactory()->createSearchModel();
        $events = $search->process();
        $this->loadView('cashierAdd');
        $this->data['events'] = $events;
    }

    public function actionCreate($params)
    {
    	$cashier = $this->getModelFactory()->createCashierModel();
    	$cashier->createReservationCashier($params);
    	$this->loadView('cashierCreate');
    }

    public function actionConfirm($params):void
    {
	    $this->hasPermission('cashier', 'admin');
        $cashier = $this->getModelFactory()->createCashierModel();
        try{
            if($cashier->checkURLParamsConfirm($params)){
                $this->redirect('cashier/confirm/');
            }
        }catch (InvalidRequestException $exception){
            $this->redirect('error');
        }

        $this->data['tickets'] = $cashier->checkSearchParameters();

        $this->loadView('cashierConfirm');
    }

	public function actionReservation($params)
    {

    }

	public function actionDefault(): void
	{
		$this->hasPermission('cashier', 'admin');
		$this->loadView('cashier');
	}
}