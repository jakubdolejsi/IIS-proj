<?php


namespace Controllers;


use Exceptions\InvalidRequestException;
use Exceptions\UpdateException;


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


	/**
	 * @param $params
	 */
	public function actionCreate($params): void
	{
    	$cashier = $this->getModelFactory()->createCashierModel();
	    try {
		    $cashier->createReservationCashier($params);
	    }
	    catch (UpdateException $e) {
	    	$this->alert($e->getMessage());
	    }
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

        $this->data['currentTime'] = date('H-i-s');
        $this->data['todayDate'] = date('Y-m-d');
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