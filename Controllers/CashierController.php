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
        $this->hasPermission('cashier', 'admin');
    	$cashier = $this->getModelFactory()->createCashierModel();
    	$cashier->createReservationCashier($params);
    	$this->loadView('cashierCreate');
    }

    public function actionSearch($params):void
    {
	    $this->hasPermission('cashier', 'admin');
        $cashier = $this->getModelFactory()->createCashierModel();
        try{
            if($cashier->checkURLParamsSearch($params)){
                $this->redirect('cashier/search/');
            }
        }catch (InvalidRequestException $exception){
            $this->redirect('error');
        }

        $this->data['currentTime'] = date('H-i-s');
        $this->data['todayDate'] = date('Y-m-d');
        $this->data['tickets'] = $cashier->checkSearchParameters();

        $this->loadView('cashierSearch');
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