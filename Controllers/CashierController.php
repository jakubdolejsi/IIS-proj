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
        $this->loadView('CashierAdd');
        $this->data['events'] = $events;
    }

    public function actionConfirm($params):void
    {
	    $this->hasPermission('cashier', 'admin');
        $cashier = $this->getModelFactory()->createCashierModel();
        try{
            if($cashier->checkURLParams($params)){
                $this->redirect('cashier/confirm/');
            }
        }catch (InvalidRequestException $exception){
            $this->redirect('Error');
        }

        $this->data['tickets'] = $cashier->checkSearchParameters();

        $this->loadView('CashierConfirm');
    }

	public function actionEdit($params): void
	{
		$this->hasPermission('cashier', 'admin');
		$updateOK = FALSE;
		$ticket = $this->getModelFactory()->createTicketManager();

		$ticketsToShow = $ticket->processUpdate($params, $updateOK);
		if (isset($updateOK)) {
			//				$this->alert('OK');
		}
		$this->loadView('CashierEdit');
		$this->data['tickets'] = $ticketsToShow;
	}

	public function actionDefault(): void
	{
		$this->hasPermission('cashier', 'admin');
		$this->loadView('Cashier');
	}
}