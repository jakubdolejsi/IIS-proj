<?php


namespace Controllers;


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
        $tickets = $cashier->checkSearchParameters();
        if($tickets === null){
            $this->data['tickets'] = [];
        }else{
            $this->data['tickets'] = $tickets;
        }


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