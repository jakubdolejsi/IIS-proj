<?php


namespace Controllers;


use Exceptions\InvalidRequestException;
use Exceptions\PaymentException;

class TicketController extends BaseController
{
	public function actionDefault(): void
	{
		$this->hasPermission('admin', 'editor', 'cashier', 'registeredUser');

		$user = $this->getModelFactory()->createUserModel();

		$email = $user->getUserInfo()->getEmail();
		$ticketManager = $this->getModelFactory()->createTicketManager();

		$ticket = $ticketManager->getTicketByEmail($email);
		$this->data['ticket'] = $ticket;
		$this->loadView('ticket');
	}

	public function actionConfirm($params){
        $this->hasPermission('admin', 'editor', 'cashier', 'registeredUser');
        $ticket = $this->getModelFactory()->createTicketManager();
        if($ticket->checkURL($params)){
            try{
                $price = $ticket->getPrice($params);
            }catch (InvalidRequestException $exception){
                $this->alert($exception->getMessage());
                $this->redirect('error');
            }
            $this->data['price'] = $price;
            $this->loadView('ticketConfirm');
            try{
                if($ticket->confirmPayment($params, $price)){
                    $this->alert('Částka uhrazena!');
                    $this->redirect('ticket');
                }
            }catch (PaymentException $exception){
                $this->alert($exception->getMessage());
            }
        }else{
            $this->redirect('error');
        }
    }
}