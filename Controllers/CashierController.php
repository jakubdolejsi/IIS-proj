<?php


namespace Controllers;


use Exceptions\InvalidRequestException;


class CashierController extends BaseController
{
    public function actionAdd($params):void
    {
        $search = $this->getModelFactory()->createSearchModel();
        $events = $search->process();
        $this->loadView('cashierAdd');
        $this->data['events'] = $events;
    }

    public function actionConfirm($params):void
    {
        $cashier = $this->getModelFactory()->createCashierModel();
        try{
            if($cashier->checkURLParams($params)){
                $this->redirect('cashier/confirm/');
            }
        }catch (InvalidRequestException $exception){
            $this->redirect('error');
        }

        $this->data['tickets'] = $cashier->checkSearchParameters();

        $this->loadView('cashierConfirm');
    }

	public function actionEdit($params): void
	{
		$updateOK = FALSE;
		$ticket = $this->getModelFactory()->createTicketManager();

		$ticketsToShow = $ticket->processUpdate($params, $updateOK);
		if (isset($updateOK)) {
			//				$this->alert('OK');
		}
		$this->loadView('cashierEdit');
		$this->data['tickets'] = $ticketsToShow;
	}

	public function actionDefault(): void
	{
		$this->loadView('cashier');
	}
}